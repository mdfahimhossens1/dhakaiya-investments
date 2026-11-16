<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\ReferralService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CalculateReferralCommission extends Command
{
    protected $signature = 'referral:commission
                            {--month= : কোন মাস (Y-m). না দিলে আগের মাস}';

    protected $description = 'আগের মাসের লাভের উপর রেফারেল কমিশন তৈরি করে (pending)';

    public function handle(ReferralService $referrals): int
    {
        $month = $this->option('month')
            ? Carbon::createFromFormat('Y-m', $this->option('month'))->startOfMonth()
            : now()->subMonthNoOverflow()->startOfMonth();

        if ($month->gte(now()->startOfMonth())) {
            $this->error('চলতি বা ভবিষ্যৎ মাসের কমিশন এখন হিসাব করা যাবে না।');

            return self::FAILURE;
        }

        $key = $month->format('Y-m');
        $this->info("Month: {$key}");

        $rows = DB::table('daily_profit_logs')
            ->join('users', 'users.id', '=', 'daily_profit_logs.user_id')
            ->whereNotNull('users.referred_by_id')
            ->whereBetween('daily_profit_logs.profit_date', [
                $month->toDateString(),
                $month->copy()->endOfMonth()->toDateString(),
            ])
            ->groupBy('daily_profit_logs.user_id')
            ->selectRaw('daily_profit_logs.user_id as uid, SUM(daily_profit_logs.profit_bdt) as total')
            ->get();

        $created = 0;
        $failed = 0;

        foreach ($rows as $row) {
            try {
                $user = User::find($row->uid);

                if ($user) {
                    $created += $referrals->onMonthlyProfit($user, $key, (string) $row->total);
                }
            } catch (\Throwable $e) {
                $failed++;
                report($e);
                $this->error("User #{$row->uid} failed: {$e->getMessage()}");
            }
        }

        $this->table(['Users checked', 'Commissions created', 'Failed'], [[count($rows), $created, $failed]]);

        return $failed ? self::FAILURE : self::SUCCESS;
    }
}