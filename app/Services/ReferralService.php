<?php

namespace App\Services;

use App\Models\ReferralCommission;
use App\Models\ReferralSetting;
use App\Models\ShareHolding;
use App\Models\ShareOrder;
use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;

class ReferralService
{
    public function __construct(
        private WalletService $wallets,
        private ShareService $shares
    ) {
    }

    /** রেফারারের বর্তমান শেয়ার বিনিয়োগ (BDT) */
    public function investmentOf(User $user): string
    {
        return (string) ShareHolding::where('user_id', $user->id)
            ->where('shares', '>', 0)
            ->sum('invested_bdt');
    }

    public function isQualified(User $user, ReferralSetting $s): bool
    {
        $min = (string) ($s->min_investment_to_qualify ?? 0);

        // শর্তহীন হলেও অন্তত কিছু শেয়ার থাকতে হবে
        if (bccomp($min, '0', 2) <= 0) {
            return ShareHolding::where('user_id', $user->id)->where('shares', '>', 0)->exists();
        }

        return bccomp($this->investmentOf($user), $min, 2) >= 0;
    }

    /** শেয়ার কেনার সাথে সাথে */
    public function onSharePurchase(ShareOrder $order): int
    {
        $source = $order->user;

        if (! $source || ! $source->referred_by_id) {
            return 0;
        }

        return $this->record($source, 'share_purchase', 'order-' . $order->id, (string) $order->net_bdt);
    }

    /** মাসিক লাভের উপর */
    public function onMonthlyProfit(User $source, string $month, string $profitBdt): int
    {
        if (! $source->referred_by_id) {
            return 0;
        }

        return $this->record($source, 'profit', $month, $profitBdt);
    }

    /** ৩ লেভেল পর্যন্ত কমিশন রেকর্ড (pending) */
    public function record(User $source, string $type, string $periodKey, string $baseBdt): int
    {
        $s = ReferralSetting::current();

        if (! $s->is_enabled) {
            return 0;
        }

        if (! in_array($s->commission_on, [$type, 'both'], true)) {
            return 0;
        }

        if (bccomp($baseBdt, '0', 2) <= 0) {
            return 0;
        }

        $created = 0;
        $current = $source;
        $seen = [$source->id];

        for ($level = 1; $level <= 3; $level++) {
            $parent = $current->referredBy;

            if (! $parent || in_array($parent->id, $seen, true)) {
                break;
            }

            $seen[] = $parent->id;
            $current = $parent;

            $percent = (string) $s->{"level{$level}_percent"};

            if (bccomp($percent, '0', 3) <= 0) {
                continue;
            }

            if ($parent->status !== 'active' || $parent->role !== 'user') {
                continue;
            }

            if (! $this->isQualified($parent, $s)) {
                continue;
            }

            $amount = bcdiv(bcmul($baseBdt, $percent, 6), '100', 2);

            // একজন রেফার করা ইউজার থেকে মোট সীমা
            $max = $s->max_commission_per_referral;

            if ($max !== null && bccomp((string) $max, '0', 2) > 0) {
                $already = (string) ReferralCommission::where('user_id', $parent->id)
                    ->where('source_user_id', $source->id)
                    ->where('status', '!=', 'cancelled')
                    ->sum('commission_bdt');

                $remaining = bcsub((string) $max, $already, 2);

                if (bccomp($remaining, '0', 2) <= 0) {
                    continue;
                }

                if (bccomp($amount, $remaining, 2) > 0) {
                    $amount = $remaining;
                }
            }

            if (bccomp($amount, '0', 2) <= 0) {
                continue;
            }

            try {
                ReferralCommission::create([
                    'user_id' => $parent->id,
                    'source_user_id' => $source->id,
                    'level' => $level,
                    'type' => $type,
                    'period_key' => $periodKey,
                    'base_bdt' => $baseBdt,
                    'percent' => $percent,
                    'commission_bdt' => $amount,
                    'status' => 'pending',
                    'release_at' => now()->addDays((int) $s->hold_days),
                ]);

                $created++;
            } catch (UniqueConstraintViolationException) {
                // আগেই তৈরি হয়েছে
            }
        }

        return $created;
    }

    /** hold শেষ হওয়া কমিশন Wallet-এ পাঠানো */
    public function releaseDue(): array
    {
        $s = ReferralSetting::current();
        $min = (string) ($s->min_commission_withdraw ?? 0);

        $stats = ['released' => 0, 'users' => 0, 'waiting' => 0];

        $userIds = ReferralCommission::where('status', 'pending')
            ->where('release_at', '<=', now())
            ->distinct()
            ->pluck('user_id');

        foreach ($userIds as $userId) {
            $user = User::find($userId);

            if (! $user || $user->status !== 'active') {
                continue;
            }

            $count = DB::transaction(function () use ($user, $min) {
                $rows = ReferralCommission::where('user_id', $user->id)
                    ->where('status', 'pending')
                    ->where('release_at', '<=', now())
                    ->lockForUpdate()
                    ->get();

                if ($rows->isEmpty()) {
                    return 0;
                }

                $total = $rows->reduce(fn ($c, $r) => bcadd($c, (string) $r->commission_bdt, 2), '0.00');

                if (bccomp($total, $min, 2) < 0) {
                    return -1; // মিনিমামের কম, জমতে থাকবে
                }

                $home = $this->shares->home($user);

                if (! $home) {
                    return 0; // রেট নেই, কমিশন pending থাকবে
                }

                $currency = $home['currency'];
                $rate = $home['rate'];
                $n = 0;

                foreach ($rows as $row) {
                    $walletAmount = bcdiv((string) $row->commission_bdt, $rate, 2);

                    if (bccomp($walletAmount, '0', 2) <= 0) {
                        continue;
                    }

                    $row->update([
                        'status' => 'paid',
                        'paid_at' => now(),
                        'wallet_currency' => $currency,
                        'exchange_rate' => $rate,
                        'wallet_amount' => $walletAmount,
                    ]);

                    $this->wallets->credit(
                        $user,
                        'referral_commission',
                        $walletAmount,
                        $row,
                        "Referral commission L{$row->level} ({$row->period_key})",
                        ['level' => $row->level, 'source_user_id' => $row->source_user_id],
                        $currency
                    );

                    $n++;
                }

                return $n;
            });

            if ($count === -1) {
                $stats['waiting']++;
            } elseif ($count > 0) {
                $stats['released'] += $count;
                $stats['users']++;
            }
        }

        return $stats;
    }
}