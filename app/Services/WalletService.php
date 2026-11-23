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

            if ($direction === 'debit') {
                if (bccomp($current, $amount, 2) < 0) {
                    throw new RuntimeException('Insufficient wallet balance.');
                }

                $newBalance = bcsub($current, $amount, 2);
            } else {
                $newBalance = bcadd($current, $amount, 2);
            }

            // একটাই ব্যালেন্স: পুরো টাকাই শেয়ার কেনা ও Withdraw-এ ব্যবহার করা যায়
            $wallet->update([
                'balance' => $newBalance,
                'withdrawable_balance' => $newBalance,
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