<?php

namespace App\Services;

use App\Models\ReferralCommission;
use App\Models\ReferralSetting;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Support\Currency;
use Illuminate\Support\Facades\DB;

class ReferralCommissionService
{
    /**
     * Upline (max 3 level) er jonno commission toiri kore.
     *
     * @param User   $source    jar karone commission hocche (buyer / profit paoa user)
     * @param string $type      'share_purchase' | 'profit'
     * @param float  $baseBdt   je BDT amount er upor commission
     * @param string $periodKey unique key: 'order-15' ba '2026-11' (duplicate atkay)
     * @return int  koyta commission toiri hoilo
     */
    public function distribute(User $source, string $type, float $baseBdt, string $periodKey): int
    {
        if (! in_array($type, ['share_purchase', 'profit'], true) || $baseBdt <= 0) {
            return 0;
        }

        $s = ReferralSetting::current();

        if (! $s->is_enabled) return 0;
        if (! in_array($s->commission_on, [$type, 'both'], true)) return 0;

        if ($type === 'share_purchase' && $baseBdt < (float) $s->min_investment_to_qualify) {
            return 0;
        }

        $created = 0;

        DB::transaction(function () use ($source, $type, $baseBdt, $periodKey, $s, &$created) {
            $current = $source;
            $visited = [$source->id];

            foreach ([1, 2, 3] as $level) {
                $referrer = $current->referrer;

                // upline shesh, ba loop hole thamo
                if (! $referrer || in_array($referrer->id, $visited, true)) {
                    break;
                }
                $visited[] = $referrer->id;

                $percent = (float) $s->{"level{$level}_percent"};

                if ($percent > 0) {
                    $commission = round($baseBdt * $percent / 100, 2);

                    // Max commission per referral (ei referrer + ei source user er total)
                    if ($s->max_commission_per_referral !== null) {
                        $already = (float) ReferralCommission::where('user_id', $referrer->id)
                            ->where('source_user_id', $source->id)
                            ->where('status', '!=', 'cancelled')
                            ->sum('commission_bdt');

                        $commission = min(
                            $commission,
                            max(0, (float) $s->max_commission_per_referral - $already)
                        );
                    }

                    if ($commission > 0) {
                        $row = ReferralCommission::firstOrCreate(
                            [
                                'user_id'        => $referrer->id,
                                'source_user_id' => $source->id,
                                'type'           => $type,
                                'period_key'     => $periodKey,
                                'level'          => $level,
                            ],
                            [
                                'base_bdt'       => $baseBdt,
                                'percent'        => $percent,
                                'commission_bdt' => $commission,
                                'status'         => 'pending',
                                'release_at'     => now()->addDays((int) $s->hold_days),
                            ]
                        );

                        if ($row->wasRecentlyCreated) $created++;
                    }
                }

                $current = $referrer;
            }
        });

        return $created;
    }

    /**
     * Pending commission ke paid kore referrer er wallet e credit kore.
     */
    public function release(ReferralCommission $commission): bool
    {
        return DB::transaction(function () use ($commission) {
            $c = ReferralCommission::whereKey($commission->id)->lockForUpdate()->first();

            if (! $c || $c->status !== 'pending') {
                return false;
            }

            $user     = $c->user;
            $currency = $user->walletCurrency();

            // BDT -> wallet currency (tomar exchange logic ekhane boshao)
            $rate   = $currency === 'BDT' ? 1.0 : (float) Currency::rateFromBdt($currency);
            $amount = round((float) $c->commission_bdt * $rate, 2);

            $wallet = Wallet::where('user_id', $user->id)
                ->where('currency', $currency)
                ->lockForUpdate()
                ->first()
                ?? $user->wallets()->create(['currency' => $currency, 'balance' => 0]);

            $wallet->increment('balance', $amount);

            WalletTransaction::create([
                'user_id'       => $user->id,
                'wallet_id'     => $wallet->id,
                'type'          => 'referral_commission',
                'amount'        => $amount,
                'currency'      => $currency,
                'balance_after' => $wallet->fresh()->balance,
                'description'   => "Level {$c->level} referral commission ({$c->type})",
            ]);

            $c->update([
                'status'          => 'paid',
                'paid_at'         => now(),
                'wallet_currency' => $currency,
                'exchange_rate'   => $rate,
                'wallet_amount'   => $amount,
            ]);

            return true;
        });
    }
}