<?php

namespace App\Services;

use App\Models\Project;
use App\Models\ShareHolding;
use App\Models\ShareOrder;
use App\Models\CurrencyRate;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ShareService
{
    public function __construct(private WalletService $wallets)
    {
    }

       /** 1 unit কারেন্সি = কত BDT। আগে Admin-এর DB, না থাকলে .env। কোনোটাই না থাকলে null */
  public function rate(string $currency): ?string
{
    $currency = strtoupper($currency);

    if ($currency === 'BDT') {
        return '1';
    }

    $rate = CurrencyRate::rateFor($currency)
        ?? config("exchange.rates.{$currency}");

    if (
        $rate === null ||
        $rate === '' ||
        !is_numeric($rate) ||
        (float) $rate <= 0
    ) {
        return null;
    }

    return (string) $rate;
}
    /** এক কারেন্সির অঙ্ক অন্য কারেন্সিতে (নিচে ছাঁটা)। rate না থাকলে null */
    public function convertAmount(string $amount, string $from, string $to): ?string
    {
        $rf = $this->rate($from);
        $rt = $this->rate($to);

        if (! $rf || ! $rt) {
            return null;
        }

        return bcdiv(bcmul($amount, $rf, 8), $rt, 2);
    }

    /** ওই প্রজেক্টে ১টা শেয়ারের দাম নির্দিষ্ট কারেন্সিতে (দেখানোর জন্য, উপরে রাউন্ড) */
    public function unitPriceIn(Project $project, string $currency): ?string
    {
        $rate = $this->rate($currency);

        if (! $rate) {
            return null;
        }

        return $this->roundUp(bcdiv((string) $project->share_price, $rate, 8));
    }

    /* ================================================================ */

    public function buy(User $user, Project $project, int $shares, string $currency): ShareOrder
    {
        $currency = strtoupper($currency);
        $rate = $this->rate($currency);

        if (! $rate) {
            throw new ShareException("{$currency} কারেন্সিতে শেয়ার কেনা এখন বন্ধ আছে।");
        }

        if ($shares < 1) {
            throw new ShareException('শেয়ারের সংখ্যা কমপক্ষে ১ হতে হবে।');
        }

        return DB::transaction(function () use ($user, $project, $shares, $currency, $rate) {
            // লক ক্রম সবসময় একই: Project -> Holding -> Wallet (deadlock এড়াতে)
            $p = Project::whereKey($project->id)->lockForUpdate()->firstOrFail();

            $this->assertOpenForPurchase($p);

            $min = min((int) $p->min_buy_shares, (int) $p->available_shares);

            if ($shares < $min) {
                throw new ShareException("এই প্রজেক্টে কমপক্ষে {$min}টি শেয়ার কিনতে হবে।");
            }

            if ($shares > $p->available_shares) {
                throw new ShareException("মাত্র {$p->available_shares}টি শেয়ার অবশিষ্ট আছে।");
            }

            $unit = (string) $p->share_price;
            $totalBdt = bcmul($unit, (string) $shares, 2);
            $walletAmount = $this->roundUp(bcdiv($totalBdt, $rate, 8));

            if (bccomp($walletAmount, '0', 2) <= 0) {
                throw new ShareException('অবৈধ মূল্য।');
            }

            $holding = $this->lockedHolding($user, $p);

            $order = ShareOrder::create([
                'user_id' => $user->id,
                'project_id' => $p->id,
                'type' => 'buy',
                'shares' => $shares,
                'unit_price_bdt' => $unit,
                'total_bdt' => $totalBdt,
                'fee_bdt' => 0,
                'net_bdt' => $totalBdt,
                'wallet_currency' => $currency,
                'exchange_rate' => $rate,
                'wallet_amount' => $walletAmount,
                'status' => 'completed',
            ]);

            try {
                $this->wallets->debit(
                    $user,
                    'share_buy',
                    $walletAmount,
                    $order,
                    "Bought {$shares} share(s) of {$p->name}",
                    ['project_id' => $p->id, 'shares' => $shares],
                    $currency
                );
            } catch (RuntimeException $e) {
                throw new ShareException('Wallet-এ পর্যাপ্ত ব্যালেন্স নেই।');
            }

            $p->update(['available_shares' => $p->available_shares - $shares]);

            $holding->update([
                'shares' => $holding->shares + $shares,
                'invested_bdt' => bcadd((string) $holding->invested_bdt, $totalBdt, 2),
                'status' => 'active',
            ]);

            return $order;
        });
    }

    /* ================================================================ */

    public function sell(User $user, Project $project, int $shares, string $currency): ShareOrder
    {
        $currency = strtoupper($currency);
        $rate = $this->rate($currency);

        if (! $rate) {
            throw new ShareException("{$currency} কারেন্সিতে রিসেল এখন বন্ধ আছে।");
        }

        if ($shares < 1) {
            throw new ShareException('শেয়ারের সংখ্যা কমপক্ষে ১ হতে হবে।');
        }

        return DB::transaction(function () use ($user, $project, $shares, $currency, $rate) {
            $p = Project::whereKey($project->id)->lockForUpdate()->firstOrFail();

            if (! $p->is_active || $p->status === 'paused') {
                throw new ShareException('এই প্রজেক্টে এখন রিসেল বন্ধ আছে।');
            }

            $holding = ShareHolding::where('user_id', $user->id)
                ->where('project_id', $p->id)
                ->lockForUpdate()
                ->first();

            if (! $holding || $holding->shares < 1) {
                throw new ShareException('আপনার এই প্রজেক্টে কোনো শেয়ার নেই।');
            }

            $sellable = $holding->shares - $holding->locked_shares;

            if ($shares > $sellable) {
                throw new ShareException(
                    "আপনি সর্বোচ্চ {$sellable}টি শেয়ার রিসেল করতে পারবেন (বাকিগুলো Lock করা)।"
                );
            }

            $unit = (string) $p->share_price;
            $gross = bcmul($unit, (string) $shares, 2);
            $feePercent = (string) config('exchange.sell_fee_percent', 0);
            $fee = bcdiv(bcmul($gross, $feePercent, 4), '100', 2);
            $netBdt = bcsub($gross, $fee, 2);

            $walletAmount = bcadd(bcdiv($netBdt, $rate, 8), '0', 2); // নিচে রাউন্ড

            if (bccomp($walletAmount, '0', 2) <= 0) {
                throw new ShareException('এই পরিমাণ রিসেল করা যাবে না।');
            }

            // বিক্রি হওয়া অংশের গড় খরচ
            $invested = (string) $holding->invested_bdt;
            $costRemoved = $shares === $holding->shares
                ? $invested
                : bcdiv(bcmul($invested, (string) $shares, 4), (string) $holding->shares, 2);

            $order = ShareOrder::create([
                'user_id' => $user->id,
                'project_id' => $p->id,
                'type' => 'sell',
                'shares' => $shares,
                'unit_price_bdt' => $unit,
                'total_bdt' => $gross,
                'fee_bdt' => $fee,
                'net_bdt' => $netBdt,
                'wallet_currency' => $currency,
                'exchange_rate' => $rate,
                'wallet_amount' => $walletAmount,
                'profit_loss_bdt' => bcsub($netBdt, $costRemoved, 2),
                'status' => 'completed',
            ]);

            $this->wallets->credit(
                $user,
                'share_sell',
                $walletAmount,
                $order,
                "Resold {$shares} share(s) of {$p->name}",
                ['project_id' => $p->id, 'shares' => $shares],
                $currency
            );

            $p->update([
                'available_shares' => min($p->total_shares, $p->available_shares + $shares),
            ]);

            $remaining = $holding->shares - $shares;

            $holding->update([
                'shares' => $remaining,
                'invested_bdt' => $remaining === 0 ? '0.00' : bcsub($invested, $costRemoved, 2),
                'status' => $remaining === 0 ? 'completed' : 'active',
            ]);

            return $order;
        });
    }

    /* ================================================================ */

    private function assertOpenForPurchase(Project $p): void
    {
        if (! $p->is_active || $p->status !== 'active') {
            throw new ShareException('এই প্রজেক্টে এখন শেয়ার কেনা যাচ্ছে না।');
        }

        if ($p->available_shares < 1) {
            throw new ShareException('এই প্রজেক্টের সব শেয়ার বিক্রি হয়ে গেছে।');
        }

        if (bccomp((string) $p->share_price, '0', 2) <= 0) {
            throw new ShareException('এই প্রজেক্টের শেয়ারের দাম এখনো নির্ধারিত হয়নি।');
        }

        if ($p->start_date && $p->start_date->gt(today())) {
            throw new ShareException('এই প্রজেক্টের বিক্রি এখনো শুরু হয়নি।');
        }

        if ($p->end_date && $p->end_date->lt(today())) {
            throw new ShareException('এই প্রজেক্টের বিক্রির সময় শেষ।');
        }
    }

    private function lockedHolding(User $user, Project $p): ShareHolding
    {
        ShareHolding::firstOrCreate(
            ['user_id' => $user->id, 'project_id' => $p->id],
            ['shares' => 0, 'locked_shares' => 0, 'invested_bdt' => 0, 'status' => 'active']
        );

        return ShareHolding::where('user_id', $user->id)
            ->where('project_id', $p->id)
            ->lockForUpdate()
            ->firstOrFail();
    }

    /** ২ দশমিকে উপরে রাউন্ড (bcmath) */
    private function roundUp(string $value): string
    {
        $cents = bcmul($value, '100', 8);
        $int = bcadd($cents, '0', 0);          // truncate

        if (bccomp($cents, $int, 8) > 0) {
            $int = bcadd($int, '1', 0);
        }

        return bcdiv($int, '100', 2);
    }

    /** ইউজারের নিজের দেশের কারেন্সি + রেট। রেট না থাকলে null */
public function home(User $user): ?array
{
    $currency = $user->walletCurrency();
    $rate = $this->rate($currency);

    return $rate ? ['currency' => $currency, 'rate' => $rate] : null;
}

/** BDT অঙ্ককে নির্দিষ্ট কারেন্সিতে দেখানো (নিচে রাউন্ড) */
public function fromBdt(string $bdt, string $currency): ?string
{
    $rate = $this->rate($currency);

    return $rate ? bcdiv($bdt, $rate, 2) : null;
}

}