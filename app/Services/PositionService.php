<?php

namespace App\Services;

use App\Models\PositionHistory;
use App\Models\PositionRank;
use App\Models\Project;
use App\Models\ShareHolding;
use App\Models\ShareLock;
use App\Models\ShareOrder;
use App\Models\User;
use App\Models\UserPosition;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PositionService
{
    private ?Collection $rankCache = null;

    /** Active Rank, level অনুযায়ী ছোট থেকে বড় */
    public function ranks(): Collection
    {
        return $this->rankCache ??= PositionRank::where('status', true)
            ->orderBy('level')
            ->get();
    }

    /* ------------------------------ মেট্রিক্স ------------------------------ */

    /** Lock করা শেয়ারের বর্তমান মূল্য (BDT) */
    public function lockedValueBdt(User $user): string
    {
        $total = '0.00';

        ShareHolding::where('user_id', $user->id)
            ->where('locked_shares', '>', 0)
            ->with('project:id,share_price')
            ->get()
            ->each(function ($h) use (&$total) {
                $total = bcadd(
                    $total,
                    bcmul((string) ($h->project?->share_price ?? 0), (string) $h->locked_shares, 2),
                    2
                );
            });

        return $total;
    }

    /** যে সরাসরি রেফারেলদের নামে এখন শেয়ার আছে */
    public function activeReferrals(User $user): int
    {
        return User::where('referred_by_id', $user->id)
            ->where('status', 'active')
            ->whereHas('shareHoldings', fn ($q) => $q->where('shares', '>', 0))
            ->count();
    }

    /** মোট রিসেল করা অঙ্ক (BDT) */
    public function resellVolume(User $user): string
    {
        return (string) ShareOrder::where('user_id', $user->id)
            ->where('type', 'sell')
            ->where('status', 'completed')
            ->sum('net_bdt');
    }

    public function metrics(User $user): array
    {
        return [
            'locked_value_bdt' => $this->lockedValueBdt($user),
            'active_referrals' => $this->activeReferrals($user),
            'resell_volume_bdt' => bcadd($this->resellVolume($user), '0', 2),
        ];
    }

    /* ------------------------------ Rank নির্ধারণ ------------------------------ */

    public function meets(PositionRank $rank, array $m): bool
    {
        // Lock ছাড়া কোনো Rank নেই
        return bccomp($m['locked_value_bdt'], '0', 2) > 0
            && bccomp($m['locked_value_bdt'], (string) $rank->min_total_investment, 2) >= 0
            && $m['active_referrals'] >= (int) $rank->min_active_referrals
            && bccomp($m['resell_volume_bdt'], (string) $rank->min_resell_volume, 2) >= 0;
    }

    public function rankFor(array $m): ?PositionRank
    {
        return $this->ranks()->filter(fn ($r) => $this->meets($r, $m))->last();
    }

    public function nextRank(?PositionRank $current): ?PositionRank
    {
        $level = $current?->level ?? 0;

        return $this->ranks()->first(fn ($r) => $r->level > $level);
    }

    /** একটা Rank-এর প্রতিটি শর্তে ইউজার কতদূর */
    public function requirements(PositionRank $rank, array $m): array
    {
        $pct = function (string $cur, string $req): float {
            if (bccomp($req, '0', 2) <= 0) {
                return 100.0;
            }

            return min(100.0, (float) bcmul(bcdiv($cur, $req, 4), '100', 2));
        };

        return [
            [
                'label' => 'Locked share value',
                'type' => 'money',
                'current' => $m['locked_value_bdt'],
                'required' => (string) $rank->min_total_investment,
                'percent' => $pct($m['locked_value_bdt'], (string) $rank->min_total_investment),
            ],
            [
                'label' => 'Active referrals',
                'type' => 'count',
                'current' => (string) $m['active_referrals'],
                'required' => (string) $rank->min_active_referrals,
                'percent' => $pct((string) $m['active_referrals'], (string) $rank->min_active_referrals),
            ],
            [
                'label' => 'Resell volume',
                'type' => 'money',
                'current' => $m['resell_volume_bdt'],
                'required' => (string) $rank->min_resell_volume,
                'percent' => $pct($m['resell_volume_bdt'], (string) $rank->min_resell_volume),
            ],
        ];
    }

    /* ------------------------------ Lock / Unlock ------------------------------ */

    public function lock(User $user, Project $project, int $count): UserPosition
    {
        if ($count < 1) {
            throw new ShareException('কমপক্ষে ১টি শেয়ার Lock করতে হবে।');
        }

        return DB::transaction(function () use ($user, $project, $count) {
            $holding = ShareHolding::where('user_id', $user->id)
                ->where('project_id', $project->id)
                ->lockForUpdate()
                ->first();

            $free = $holding ? $holding->shares - $holding->locked_shares : 0;

            if ($free < $count) {
                throw new ShareException("Lock করার মতো মাত্র {$free}টি শেয়ার আছে।");
            }

            $holding->update(['locked_shares' => $holding->locked_shares + $count]);

            ShareLock::create([
                'user_id' => $user->id,
                'share_holding_id' => $holding->id,
                'project_id' => $project->id,
                'shares' => $count,
                'status' => 'locked',
                'locked_at' => now(),
            ]);

            return $this->recalculate($user);
        });
    }

    /** Unlock করলে Position নেমে যেতে পারে */
    public function unlock(User $user, Project $project, int $count): UserPosition
    {
        if ($count < 1) {
            throw new ShareException('কমপক্ষে ১টি শেয়ার Unlock করতে হবে।');
        }

        return DB::transaction(function () use ($user, $project, $count) {
            $holding = ShareHolding::where('user_id', $user->id)
                ->where('project_id', $project->id)
                ->lockForUpdate()
                ->first();

            if (! $holding || $holding->locked_shares < $count) {
                throw new ShareException('এতগুলো শেয়ার Lock করা নেই।');
            }

            $remaining = $count;

            // সবচেয়ে নতুন Lock আগে খুলবে
            $locks = ShareLock::where('share_holding_id', $holding->id)
                ->where('status', 'locked')
                ->orderByDesc('id')
                ->lockForUpdate()
                ->get();

            foreach ($locks as $lock) {
                if ($remaining <= 0) {
                    break;
                }

                if ($lock->shares <= $remaining) {
                    $remaining -= $lock->shares;
                    $lock->update(['status' => 'released', 'released_at' => now()]);
                } else {
                    $lock->update(['shares' => $lock->shares - $remaining]);
                    $remaining = 0;
                }
            }

            $holding->update(['locked_shares' => $holding->locked_shares - $count]);

            return $this->recalculate($user);
        });
    }

    /* ------------------------------ পুনর্গণনা ------------------------------ */

    /** Rank বদলালে history লেখে, achieved_at রিসেট করে */
    public function recalculate(User $user): UserPosition
    {
        return DB::transaction(function () use ($user) {
            UserPosition::firstOrCreate(['user_id' => $user->id]);

            $pos = UserPosition::where('user_id', $user->id)->lockForUpdate()->first();

            $m = $this->metrics($user);
            $new = $this->rankFor($m);

            $oldId = $pos->position_rank_id;
            $newId = $new?->id;

            if ($oldId != $newId) {
                $oldLevel = $oldId ? (int) (PositionRank::find($oldId)?->level ?? 0) : 0;
                $newLevel = (int) ($new?->level ?? 0);

                PositionHistory::create([
                    'user_id' => $user->id,
                    'from_rank_id' => $oldId,
                    'to_rank_id' => $newId,
                    'action' => $newLevel > $oldLevel ? 'promoted' : 'demoted',
                    'locked_value_bdt' => $m['locked_value_bdt'],
                ]);

                $pos->position_rank_id = $newId;
                $pos->achieved_at = $newId ? now() : null;
                $pos->reward_paid_at = null;
            }

            $pos->locked_value_bdt = $m['locked_value_bdt'];
            $pos->save();

            return $pos->load('rank');
        });
    }
}