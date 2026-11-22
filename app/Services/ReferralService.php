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

private const TYPE = 'profit';
private const MAX_LEVEL = 1;

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

    /**
 * Profit Wallet-এ credit হওয়ার ঠিক পরে (একই DB transaction-এর ভেতরে) call হবে।
 * $profitRef = প্রতিটি Profit Transaction-এর unique রেফারেন্স, যেমন "profit-{wallet_transaction_id}"
 */
public function onProfitCredited(User $source, string $profitBdt, string $profitRef): int
{
    if (! $source->referred_by_id) {
        return 0;
    }

    return $this->record($source, $profitRef, $profitBdt);
}

public function record(User $source, string $profitRef, string $profitBdt): int
{
    $s = ReferralSetting::current();

    if (! $s->is_enabled) {
        return 0;
    }

    // শুধু বাস্তব Profit-এর উপর; লস বা ০ হলে কমিশন নেই
    if (bccomp($profitBdt, '0', 2) <= 0) {
        return 0;
    }

    $created = 0;
    $current = $source;
    $seen = [$source->id];

    for ($level = 1; $level <= self::MAX_LEVEL; $level++) {
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

        $amount = bcdiv(bcmul($profitBdt, $percent, 6), '100', 2);

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
                'user_id'        => $parent->id,
                'source_user_id' => $source->id,
                'level'          => $level,
                'type'           => self::TYPE,
                'period_key'     => $profitRef,   // প্রতি Profit Transaction-এ একবারই
                'base_bdt'       => $profitBdt,
                'percent'        => $percent,
                'commission_bdt' => $amount,
                'status'         => 'pending',
                'release_at'     => now()->addDays((int) $s->hold_days),
            ]);

            $created++;
        } catch (UniqueConstraintViolationException) {
            // একই Profit থেকে আগেই তৈরি হয়েছে, তাই আর হবে না
        }
    }

    return $created;
}

/** Profit বাতিল/Reversal হলে সংশ্লিষ্ট কমিশন সমন্বয় */
public function reverseForProfit(string $profitRef): array
{
    $stats = ['cancelled' => 0, 'clawed_back' => 0];

    DB::transaction(function () use ($profitRef, &$stats) {
        $rows = ReferralCommission::where('type', self::TYPE)
            ->where('period_key', $profitRef)
            ->where('status', '!=', 'cancelled')
            ->lockForUpdate()
            ->get();

        foreach ($rows as $row) {
            if ($row->status === 'pending') {
                $row->update(['status' => 'cancelled']);
                $stats['cancelled']++;
                continue;
            }

            // paid হয়ে গেলে wallet থেকে ফেরত নিতে হবে
            // WalletService-এ আপনার debit method-এর নাম/signature অনুযায়ী মিলিয়ে নিন
            $this->wallets->debit(
                $row->user,
                'referral_commission_reversal',
                (string) $row->wallet_amount,
                $row,
                "Referral commission reversed L{$row->level} ({$row->period_key})",
                ['level' => $row->level, 'source_user_id' => $row->source_user_id],
                $row->wallet_currency
            );

            $row->update(['status' => 'cancelled']);
            $stats['clawed_back']++;
        }
    });

    return $stats;
}

}