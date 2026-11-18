<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class WalletService
{
    /** এই ধরনের credit তোলার মতো আয়ের অংশে যায়। বাকি সব (deposit, share_sell) Principal-এ */
    private const WITHDRAWABLE_CREDITS = ['daily_profit', 'referral_commission', 'reward', 'withdraw_refund'];

    /**
     * meta['bucket'] = 'earnings' | 'locked' দিলে type-এর নিয়ম বদলে ওই অংশেই কাজ হয় (কনভার্সনের জন্য)
     */
    public function credit(
        User $user,
        string $type,
        string|float|int $amount,
        ?Model $source = null,
        ?string $description = null,
        array $meta = [],
        ?string $currency = null
    ): WalletTransaction {
        return $this->apply($user, 'credit', $type, $amount, $source, $description, $meta, $currency);
    }

    public function debit(
        User $user,
        string $type,
        string|float|int $amount,
        ?Model $source = null,
        ?string $description = null,
        array $meta = [],
        ?string $currency = null
    ): WalletTransaction {
        return $this->apply($user, 'debit', $type, $amount, $source, $description, $meta, $currency);
    }

    private function apply(
        User $user,
        string $direction,
        string $type,
        string|float|int $amount,
        ?Model $source,
        ?string $description,
        array $meta,
        ?string $currency
    ): WalletTransaction {
        $amount = number_format((float) $amount, 2, '.', '');

        if (bccomp($amount, '0', 2) <= 0) {
            throw new RuntimeException('Amount must be greater than zero.');
        }

        $currency = strtoupper($currency ?: $user->walletCurrency());

        if (! preg_match('/^[A-Z]{3}$/', $currency)) {
            throw new RuntimeException('Invalid currency.');
        }

        return DB::transaction(function () use (
            $user, $direction, $type, $amount, $source, $description, $meta, $currency
        ) {
            Wallet::firstOrCreate(
                ['user_id' => $user->id, 'currency' => $currency],
                ['balance' => 0, 'withdrawable_balance' => 0]
            );

            $wallet = Wallet::where('user_id', $user->id)
                ->where('currency', $currency)
                ->lockForUpdate()
                ->first();

            $current = (string) $wallet->balance;
            $withdrawable = (string) $wallet->withdrawable_balance;
            $bucket = $meta['bucket'] ?? null;

            if ($direction === 'debit') {
                if (bccomp($current, $amount, 2) < 0) {
                    throw new RuntimeException('Insufficient wallet balance.');
                }

                $principal = bcsub($current, $withdrawable, 2);

                if ($type === 'withdraw' || $bucket === 'earnings') {
                    if (bccomp($withdrawable, $amount, 2) < 0) {
                        throw new RuntimeException('Insufficient withdrawable balance.');
                    }

                    $withdrawable = bcsub($withdrawable, $amount, 2);
                } elseif ($bucket === 'locked') {
                    if (bccomp($principal, $amount, 2) < 0) {
                        throw new RuntimeException('Insufficient locked balance.');
                    }
                } elseif (bccomp($amount, $principal, 2) > 0) {
                    // শেয়ার কেনা ইত্যাদি: আগে Principal, বাকিটা আয় থেকে
                    $withdrawable = bcsub($withdrawable, bcsub($amount, $principal, 2), 2);
                }

                $newBalance = bcsub($current, $amount, 2);
            } else {
                $newBalance = bcadd($current, $amount, 2);

                $toWithdrawable = $bucket !== null
                    ? $bucket === 'earnings'
                    : (in_array($type, self::WITHDRAWABLE_CREDITS, true) || (($meta['withdrawable'] ?? false) === true));

                if ($toWithdrawable) {
                    $withdrawable = bcadd($withdrawable, $amount, 2);
                }
            }

            $wallet->update([
                'balance' => $newBalance,
                'withdrawable_balance' => $withdrawable,
            ]);

            return WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'user_id' => $user->id,
                'direction' => $direction,
                'type' => $type,
                'amount' => $amount,
                'balance_after' => $newBalance,
                'currency' => $currency,
                'source_type' => $source?->getMorphClass(),
                'source_id' => $source?->getKey(),
                'description' => $description,
                'meta' => $meta ?: null,
            ]);
        });
    }
}