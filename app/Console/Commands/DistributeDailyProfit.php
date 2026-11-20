<?php

namespace App\Console\Commands;

use App\Models\DailyProfitLog;
use App\Models\PositionRank;
use App\Models\ShareHolding;
use App\Models\ShareOrder;
use App\Models\UserPosition;
use App\Services\ShareService;
use App\Services\WalletService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DistributeDailyProfit extends Command
{
    protected $signature = 'profit:distribute
                            {--date= : শুধু এই তারিখের লাভ (Y-m-d). না দিলে বাদ পড়া সব দিন}
                            {--dry : শুধু হিসাব দেখাবে, কিছু save করবে না}';

    protected $description = 'ধরা শেয়ারের দৈনিক লাভ ইউজারদের Wallet-এ যোগ করে (বাদ পড়া দিনও পূরণ করে, ডবল হয় না)';

    private array $reasons = [];

    public function handle(WalletService $wallets, ShareService $shares): int
    {
        $lock = Cache::lock('profit-distribute', 900);

        if (! $lock->get()) {
            $this->warn('অন্য একটা রান চলছে।');

            return self::SUCCESS;
        }

        try {
            return $this->distribute($wallets, $shares);
        } finally {
            $lock->release();
        }
    }

    private function distribute(WalletService $wallets, ShareService $shares): int
    {
        $dry = (bool) $this->option('dry');

        if ($this->option('date')) {
            $d = Carbon::parse($this->option('date'))->startOfDay();

            if ($d->gte(today())) {
                $this->error('আজ বা ভবিষ্যতের তারিখের লাভ দেওয়া যাবে না।');

                return self::FAILURE;
            }

            $dates = [$d];
        } else {
            $dates = $this->pendingDates();
        }

        if (! $dates) {
            $this->info('কোনো দিনের লাভ বাকি নেই।');

            return self::SUCCESS;
        }

        $stats = ['paid' => 0, 'duplicate' => 0, 'skipped' => 0, 'failed' => 0];
        $totalBdt = '0.00';

        foreach ($dates as $date) {
            $this->info(($dry ? '[DRY RUN] ' : '') . 'Profit date: ' . $date->toDateString());

            ShareHolding::query()
                ->with(['project', 'user'])
                ->chunkById(200, function ($holdings) use (&$stats, &$totalBdt, $date, $dry, $wallets, $shares) {
                    foreach ($holdings as $holding) {
                        try {
                            $result = $this->processHolding($holding, $date, $dry, $wallets, $shares);

                            if (is_array($result)) {
                                $stats['paid']++;
                                $totalBdt = bcadd($totalBdt, $result['profit_bdt'], 2);
                            } else {
                                $stats[$result]++;
                            }
                        } catch (\Throwable $e) {
                            $stats['failed']++;
                            report($e);
                            $this->error("Holding #{$holding->id} failed: {$e->getMessage()}");
                        }
                    }
                });
        }

        $this->table(
            ['Days', 'Paid', 'Already done', 'Skipped', 'Failed', 'Total (BDT)'],
            [[count($dates), $stats['paid'], $stats['duplicate'], $stats['skipped'], $stats['failed'], $totalBdt]]
        );

        if ($this->reasons) {
            $this->warn('Skipped because:');
            foreach ($this->reasons as $reason => $count) {
                $this->line("  - {$reason}: {$count}");
            }
        }

        return $stats['failed'] > 0 ? self::FAILURE : self::SUCCESS;
    }

    /** শেষ লাভের পরের দিন থেকে গতকাল পর্যন্ত (সর্বোচ্চ ৩১ দিন) */
    private function pendingDates(): array
    {
        $yesterday = now()->subDay()->startOfDay();
        $last = DailyProfitLog::max('profit_date');

        if ($last) {
            $start = Carbon::parse($last)->startOfDay()->addDay();
        } else {
            $first = ShareOrder::where('type', 'buy')->min('created_at');

            if (! $first) {
                return [];
            }

            // কেনার পরদিন থেকে লাভ শুরু
            $start = Carbon::parse($first)->startOfDay()->addDay();
        }

        $floor = $yesterday->copy()->subDays(30);

        if ($start->lt($floor)) {
            $this->warn('৩১ দিনের বেশি পুরোনো দিন এখানে ধরা হয়নি। দরকার হলে --date দিয়ে আলাদা চালান।');
            $start = $floor;
        }

        $dates = [];

        for ($d = $start->copy(); $d->lte($yesterday); $d->addDay()) {
            $dates[] = $d->copy();
        }

        return $dates;
    }

    private function skip(string $reason): string
    {
        $this->reasons[$reason] = ($this->reasons[$reason] ?? 0) + 1;

        return 'skipped';
    }

    /** @return array|string  array = paid, string = duplicate|skipped */
    private function processHolding(
        ShareHolding $holding,
        Carbon $date,
        bool $dry,
        WalletService $wallets,
        ShareService $shares
    ): array|string {
        $project = $holding->project;
        $user = $holding->user;

        if (! $project || ! $user) {
            return $this->skip('project or user missing');
        }

        if ($user->status !== 'active') {
            return $this->skip('user not active');
        }

        if (! $project->is_active || $project->status !== 'active') {
            return $this->skip("project '{$project->name}' is not active (status/is_active)");
        }

        if ($project->start_date && $project->start_date->gt($date)) {
            return $this->skip('project start_date is after the profit date');
        }

        if ($project->end_date && $project->end_date->lt($date)) {
            return $this->skip('project end_date is before the profit date');
        }

        if (bccomp((string) $project->daily_profit_percent, '0', 3) <= 0) {
            return $this->skip("project '{$project->name}' daily_profit_percent is 0");
        }

        if (DailyProfitLog::where('share_holding_id', $holding->id)->whereDate('profit_date', $date)->exists()) {
            return 'duplicate';
        }

        // ওই দিনের শুরু ও শেষ, দুই সময়েই যতটা শেয়ার ধরা ছিল তার কম সংখ্যাটাই লাভ পায়
        $start = $date->copy()->startOfDay()->toDateTimeString();
        $end = $date->copy()->startOfDay()->addDay()->toDateTimeString();

        $row = ShareOrder::where('user_id', $holding->user_id)
            ->where('project_id', $holding->project_id)
            ->where('status', 'completed')
            ->selectRaw(
                "COUNT(*) AS n,
                 COALESCE(SUM(CASE WHEN type='buy'  AND created_at < ? THEN shares ELSE 0 END),0)
               - COALESCE(SUM(CASE WHEN type='sell' AND created_at < ? THEN shares ELSE 0 END),0) AS at_start,
                 COALESCE(SUM(CASE WHEN type='buy'  AND created_at < ? THEN shares ELSE 0 END),0)
               - COALESCE(SUM(CASE WHEN type='sell' AND created_at < ? THEN shares ELSE 0 END),0) AS at_end",
                [$start, $start, $end, $end]
            )->first();

        if ((int) ($row->n ?? 0) === 0) {
            // অর্ডার নেই (পুরোনো/টেস্ট ডেটা): হোল্ডিং আগে থেকে থাকলে সেটাই ধরা
            $eligible = $holding->created_at && $holding->created_at->lt($date->copy()->startOfDay())
                ? (int) $holding->shares : 0;
        } else {
            $eligible = max(0, min((int) $row->at_start, (int) $row->at_end));
        }

        if ($eligible < 1) {
            return $this->skip('no shares held for the full day (a new purchase earns from the next day)');
        }

        $price = (string) $project->share_price;
        $percent = (string) $project->daily_profit_percent;

        $profitBdt = bcdiv(bcmul(bcmul($price, (string) $eligible, 2), $percent, 6), '100', 2);

        // Rank-এর profit boost
        $rankId = UserPosition::where('user_id', $holding->user_id)->value('position_rank_id');

        if ($rankId) {
            $boost = (string) (PositionRank::where('id', $rankId)->where('status', true)->value('profit_boost_percent') ?? 0);

            if (bccomp($boost, '0', 2) > 0) {
                $profitBdt = bcdiv(bcmul($profitBdt, bcadd('100', $boost, 2), 6), '100', 2);
            }
        }

        if (bccomp($profitBdt, '0', 2) <= 0) {
            return $this->skip('profit amount rounds to zero');
        }

        // লাভ যাবে ইউজারের নিজের দেশের কারেন্সি Wallet-এর Earnings অংশে
        $home = $shares->home($user);

        if (! $home) {
            return $this->skip("no exchange rate for {$user->walletCurrency()} (set it in Admin → Exchange Rates)");
        }

        $currency = $home['currency'];
        $rate = $home['rate'];
        $walletAmount = bcdiv($profitBdt, $rate, 2);

        if (bccomp($walletAmount, '0', 2) <= 0) {
            return $this->skip('wallet amount rounds to zero');
        }

        if ($dry) {
            $this->line("  user #{$user->id} | {$project->name} | {$eligible} shares => {$walletAmount} {$currency}");

            return ['profit_bdt' => $profitBdt];
        }

               try {
            DB::transaction(function () use (
                $holding, $user, $project, $date, $eligible, $price, $percent,
                $profitBdt, $currency, $rate, $walletAmount, $wallets
            ) {
                $log = DailyProfitLog::create([
                    'share_holding_id' => $holding->id,
                    'user_id' => $user->id,
                    'project_id' => $project->id,
                    'profit_date' => $date->toDateString(),
                    'shares' => $eligible,
                    'share_price_bdt' => $price,
                    'profit_percent' => $percent,
                    'profit_bdt' => $profitBdt,
                    'wallet_currency' => $currency,
                    'exchange_rate' => $rate,
                    'wallet_amount' => $walletAmount,
                ]);

                $wallets->credit(
                    $user, 'daily_profit', $walletAmount, $log,
                    "Daily profit {$date->toDateString()} - {$project->name}",
                    ['project_id' => $project->id, 'shares' => $eligible],
                    $currency
                );
            });
        } catch (UniqueConstraintViolationException) {
            return 'duplicate';
        }

        // Profit dewar por user ke notification
        Notify::user(
            $user,
            'Daily Profit Received',
            'You received ' . Notify::money($walletAmount, $currency)
                . " profit from {$project->name} ({$eligible} shares) for " . $date->format('d M Y') . '.',
            'success'
        );

        return ['profit_bdt' => $profitBdt];
    }
}