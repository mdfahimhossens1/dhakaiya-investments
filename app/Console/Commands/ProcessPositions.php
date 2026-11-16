<?php

namespace App\Console\Commands;

use App\Models\ShareHolding;
use App\Models\User;
use App\Models\UserPosition;
use App\Services\PositionService;
use App\Services\RewardService;
use Illuminate\Console\Command;

class ProcessPositions extends Command
{
    protected $signature = 'position:process';

    protected $description = 'সবার Position আবার হিসাব করে এবং যোগ্যদের Position bonus দেয়';

    public function handle(PositionService $positions, RewardService $rewards): int
    {
        // ১) শেয়ারের দাম/রেফারেল/রিসেল বদলালে Position বদলাতে পারে
        $ids = ShareHolding::where('locked_shares', '>', 0)->pluck('user_id')
            ->merge(UserPosition::whereNotNull('position_rank_id')->pluck('user_id'))
            ->unique();

        $recalculated = 0;
        $failed = 0;

        foreach ($ids as $id) {
            try {
                if ($user = User::find($id)) {
                    $positions->recalculate($user);
                    $recalculated++;
                }
            } catch (\Throwable $e) {
                $failed++;
                report($e);
            }
        }

        // ২) নির্ধারিত দিন Rank ধরে রাখলে bonus (একবারই)
        $paid = 0;

        UserPosition::whereNotNull('position_rank_id')
            ->whereNull('reward_paid_at')
            ->whereNotNull('achieved_at')
            ->with(['rank', 'user'])
            ->chunkById(200, function ($rows) use ($rewards, &$paid, &$failed) {
                foreach ($rows as $pos) {
                    try {
                        $rank = $pos->rank;

                        if (! $rank || ! $rank->status || ! $pos->user || $pos->user->status !== 'active') {
                            continue;
                        }

                        $dueAt = $pos->achieved_at->copy()->addDays((int) $rank->lock_period_days);

                        if ($dueAt->gt(now())) {
                            continue;
                        }

                        if (bccomp((string) $rank->bonus_amount, '0', 2) > 0) {
                            $rewards->grant(
                                $pos->user,
                                'position',
                                'rank-' . $rank->id . '-' . $pos->achieved_at->format('YmdHis'),
                                (string) $rank->bonus_amount,
                                'Position bonus: ' . $rank->name,
                                ['rank_id' => $rank->id, 'level' => $rank->level]
                            );
                        }

                        $pos->update(['reward_paid_at' => now()]);
                        $paid++;
                    } catch (\Throwable $e) {
                        // যেমন exchange rate নেই: পরের রানে আবার চেষ্টা হবে
                        $failed++;
                        report($e);
                    }
                }
            });

        $this->table(['Recalculated', 'Bonus paid', 'Failed'], [[$recalculated, $paid, $failed]]);

        return $failed ? self::FAILURE : self::SUCCESS;
    }
}