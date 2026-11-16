<?php

namespace App\Services;

use App\Models\RewardLog;
use App\Models\ShareOrder;
use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;

class RewardService
{
    public function __construct(
        private WalletService $wallets,
        private ShareService $shares
    ) {
    }

    /** একই (user, category, period) এ দ্বিতীয়বার দিলে null ফেরত আসে, টাকা যোগ হয় না */
    public function grant(
        User $user,
        string $category,
        string $periodKey,
        string $amountBdt,
        string $title,
        array $meta = []
    ): ?RewardLog {
        if (bccomp($amountBdt, '0', 2) <= 0) {
            return null;
        }

        $home = $this->shares->home($user);

        if (! $home) {
            // null নয়, exception: যাতে ডেকে-থাকা কমান্ড "পেমেন্ট হয়েছে" ধরে না নেয়
            throw new ShareException("{$user->walletCurrency()} এর exchange rate নেই।");
        }

        $currency = $home['currency'];
        $rate = $home['rate'];

        $walletAmount = bcdiv($amountBdt, $rate, 2);

        if (bccomp($walletAmount, '0', 2) <= 0) {
            return null;
        }

        try {
            return DB::transaction(function () use (
                $user, $category, $periodKey, $amountBdt, $title, $meta, $currency, $rate, $walletAmount
            ) {
                $log = RewardLog::create([
                    'user_id' => $user->id,
                    'category' => $category,
                    'period_key' => $periodKey,
                    'title' => $title,
                    'amount_bdt' => $amountBdt,
                    'wallet_currency' => $currency,
                    'exchange_rate' => $rate,
                    'wallet_amount' => $walletAmount,
                    'meta' => $meta ?: null,
                ]);

                $this->wallets->credit(
                    $user, 'reward', $walletAmount, $log, $title,
                    $meta + ['category' => $category], $currency
                );

                return $log;
            });
        } catch (UniqueConstraintViolationException) {
            return null;
        }
    }
}