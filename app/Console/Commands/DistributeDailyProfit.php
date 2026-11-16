<?php

namespace App\Console\Commands;

use App\Models\DailyProfitLog;
use App\Models\ShareHolding;
use App\Models\ShareOrder;
use App\Models\PositionRank;
use App\Models\UserPosition;
use App\Services\ShareService;
use App\Services\WalletService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;

class DistributeDailyProfit extends Command
{
    protected $signature = 'profit:distribute
                            {--date= : কোন তারিখের লাভ (Y-m-d). না দিলে গতকাল}
                            {--dry : শুধু হিসাব দেখাবে, কিছু save করবে না}';

    protected $description = 'ধরা শেয়ারের দৈনিক লাভ ইউজারদের Wallet-এ যোগ করে (প্রতিদিন একবারই)';

    public function handle(WalletService $wallets, ShareService $shares): int
    {
        $date = $this->option('date')
            ? Carbon::parse($this->option('date'))->startOfDay()
            : now()->subDay()->startOfDay();

        if ($date->gte(today())) {
            $this->error('আজ বা ভবিষ্যতের তারিখের লাভ দেওয়া যাবে না।');

            return self::FAILURE;
        }

        $dry = (bool) $this->option('dry');
        $stats = ['paid' => 0, 'duplicate' => 0, 'skipped' => 0, 'failed' => 0];
        $totalBdt = '0.00';

        $this->info(($dry ? '[DRY RUN] ' : '') . 'Profit date: ' . $date->toDateString());

        ShareHolding::query()
            ->where('shares', '>', 0)
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

        $this->table(
            ['Paid', 'Already done', 'Skipped', 'Failed', 'Total (BDT)'],
            [[$stats['paid'], $stats['duplicate'], $stats['skipped'], $stats['failed'], $totalBdt]]
        );

        return $stats['failed'] > 0 ? self::FAILURE : self::SUCCESS;
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

        if (! $project || ! $user || $user->status !== 'active') {
            return 'skipped';
        }

        if (! $project->is_active || $project->status !== 'active') {
            return 'skipped';
        }

        if ($project->start_date && $project->start_date->gt($date)) {
            return 'skipped';
        }

        if ($project->end_date && $project->end_date->lt($date)) {
            return 'skipped';
        }

        if (bccomp((string) $project->daily_profit_percent, '0', 3) <= 0) {
            return 'skipped';
        }

        // আগেই দেওয়া হয়েছে কিনা (সস্তা চেক; আসল গার্ড DB unique)
        if (DailyProfitLog::where('share_holding_id', $holding->id)
            ->whereDate('profit_date', $date)->exists()) {
            return 'duplicate';
        }

        // ওই দিন বা পরে কেনা শেয়ার ওই দিনের লাভ পাবে না
        $boughtSince = (int) ShareOrder::where('user_id', $holding->user_id)
            ->where('project_id', $holding->project_id)
            ->where('type', 'buy')
            ->where('created_at', '>=', $date->copy()->startOfDay())
            ->sum('shares');

        $eligible = max(0, $holding->shares - $boughtSince);

        if ($eligible < 1) {
            return 'skipped';
        }

        $price = (string) $project->share_price;
        $percent = (string) $project->daily_profit_percent;

        // শেয়ার মূল্য × শেয়ার × % ÷ 100 (নিচে রাউন্ড)
        $profitBdt = bcdiv(
            bcmul(bcmul($price, (string) $eligible, 2), $percent, 6),
            '100',
            2
        );
        // Rank-এর profit boost (থাকলে)
        $rankId = UserPosition::where('user_id', $holding->user_id)->value('position_rank_id');

        if ($rankId) {
            $boost = (string) (PositionRank::where('id', $rankId)->where('status', true)
                ->value('profit_boost_percent') ?? 0);

            if (bccomp($boost, '0', 2) > 0) {
                $profitBdt = bcdiv(bcmul($profitBdt, bcadd('100', $boost, 2), 6), '100', 2);
            }
        }
        if (bccomp($profitBdt, '0', 2) <= 0) {
            return 'skipped';
        }

        // লাভ যাবে ইউজারের নিজের দেশের কারেন্সি Wallet-এ
        $home = $shares->home($user);

        if (! $home) {
            $this->warn("User #{$user->id}: {$user->walletCurrency()} এর exchange rate নেই, বাদ গেছে।");

            return 'skipped';
        }

        $currency = $home['currency'];
        $rate = $home['rate'];

        $walletAmount = bcdiv($profitBdt, $rate, 2); // নিচে রাউন্ড

        if (bccomp($walletAmount, '0', 2) <= 0) {
            return 'skipped';
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
                    $user,
                    'daily_profit',
                    $walletAmount,
                    $log,
                    "Daily profit {$date->toDateString()} - {$project->name}",
                    ['project_id' => $project->id, 'shares' => $eligible],
                    $currency
                );
            });
        } catch (UniqueConstraintViolationException) {
            return 'duplicate'; // অন্য প্রসেস আগেই দিয়ে ফেলেছে
        }

        return ['profit_bdt' => $profitBdt];
    }
}