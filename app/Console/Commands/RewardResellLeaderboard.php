<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\LeaderboardService;
use App\Services\RewardService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RewardResellLeaderboard extends Command
{
    protected $signature = 'leaderboard:reward {--month= : Y-m, না দিলে আগের মাস}';

    protected $description = 'আগের মাসের রিসেল Leaderboard-এর শীর্ষ ইউজারদের reward দেয়';

    public function handle(LeaderboardService $board, RewardService $rewards): int
    {
        $month = $this->option('month')
            ? Carbon::createFromFormat('Y-m', $this->option('month'))->startOfMonth()
            : now()->subMonthNoOverflow()->startOfMonth();

        if ($month->gte(now()->startOfMonth())) {
            $this->error('চলতি বা ভবিষ্যৎ মাসের reward এখন দেওয়া যাবে না।');

            return self::FAILURE;
        }

        $key = $month->format('Y-m');
        $prizes = config('rewards.resell.top', []);
        $minVolume = (string) config('rewards.resell.min_volume_bdt', 0);

        $top = $board->resellTop($month, $month->copy()->endOfMonth(), max(1, count($prizes)));

        $paid = 0;

        foreach ($top->values() as $i => $row) {
            $rank = $i + 1;

            if (! isset($prizes[$rank]) || bccomp((string) $row->volume, $minVolume, 2) < 0) {
                continue;
            }

            $user = User::find($row->user_id);

            if (! $user) {
                continue;
            }

            $log = $rewards->grant(
                $user,
                'resell',
                "resell-{$key}-rank{$rank}",
                (string) $prizes[$rank],
                "Resell leaderboard #{$rank} ({$key})",
                ['rank' => $rank, 'month' => $key]
            );

            if ($log) {
                $paid++;
            }
        }

        $this->info("Month {$key}: {$paid} reward(s) paid.");

        return self::SUCCESS;
    }
}