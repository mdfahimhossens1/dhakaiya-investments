<?php

namespace App\Services;

use App\Models\Project;
use App\Models\ShareHolding;
use App\Models\ShareListing;
use App\Models\ShareOrder;
use App\Models\ShareTrade;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ShareMarketplaceService
{
    private const MAX_ACTIVE_LISTINGS = 20;

    public function __construct(
        private WalletService $wallets,
        private ShareService $shares
    ) {
    }

    /** কোম্পানির কমিশন (%) */
    public function feePercent(): string
    {
        return (string) config('exchange.p2p_commission_percent', 0);
    }

    /** ক্রেতার কারেন্সিতে প্রতি শেয়ারের দাম (দেখানোর জন্য, উপরে রাউন্ড)। রেট না থাকলে null */
    public function unitPriceIn(ShareListing $listing, string $currency): ?string
    {
        $rate = $this->shares->rate($currency);

        return $rate ? $this->ceil2(bcdiv((string) $listing->unit_price_bdt, $rate, 8)) : null;
    }

    /* ================================================================ */
    /*  Post for Sale                                                   */
    /* ================================================================ */

    public function post(User $seller, Project $project, int $qty, string $price, string $currency): ShareListing
    {
                if ($seller->role !== 'user') {
            throw new ShareException('এই অ্যাকাউন্ট দিয়ে শেয়ার বিক্রি করা যায় না।');
        }
        $currency = strtoupper($currency);
        $rate = $this->shares->rate($currency);

        if (! $rate) {
            throw new ShareException("{$currency} কারেন্সির রেট সেট নেই, এখন পোস্ট করা যাচ্ছে না।");
        }

        if ($qty < 1) {
            throw new ShareException('শেয়ারের সংখ্যা কমপক্ষে ১ হতে হবে।');
        }

        $price = number_format((float) $price, 2, '.', '');

        if (bccomp($price, '0', 2) <= 0) {
            throw new ShareException('দাম শূন্যের বেশি হতে হবে।');
        }

        // বিক্রেতার কারেন্সির দাম → BDT (ভিত্তি মূল্য)
        $unitBdt = bcmul($price, $rate, 4);

        if (bccomp($unitBdt, '0', 4) <= 0) {
            throw new ShareException('এই দামে পোস্ট করা যাবে না।');
        }

        return DB::transaction(function () use ($seller, $project, $qty, $price, $currency, $unitBdt) {
            $p = Project::findOrFail($project->id);
            $this->assertTradable($p);

            $active = ShareListing::where('seller_id', $seller->id)->where('status', 'active')->count();

            if ($active >= self::MAX_ACTIVE_LISTINGS) {
                throw new ShareException('একসাথে সর্বোচ্চ ' . self::MAX_ACTIVE_LISTINGS . 'টি পোস্ট চালু রাখা যায়।');
            }

            $holding = ShareHolding::where('user_id', $seller->id)
                ->where('project_id', $p->id)
                ->lockForUpdate()
                ->first();

            if (! $holding || $holding->shares < 1) {
                throw new ShareException('আপনার এই প্রজেক্টে কোনো শেয়ার নেই।');
            }

            $sellable = $holding->shares - $holding->locked_shares - (int) $holding->listed_shares;

            if ($qty > $sellable) {
                throw new ShareException("আপনি সর্বোচ্চ {$sellable}টি শেয়ার পোস্ট করতে পারবেন (বাকিগুলো Lock বা অন্য পোস্টে আছে)।");
            }

            $holding->forceFill(['listed_shares' => (int) $holding->listed_shares + $qty])->save();

            return ShareListing::create([
                'seller_id' => $seller->id,
                'project_id' => $p->id,
                'shares' => $qty,
                'remaining_shares' => $qty,
                'unit_price_bdt' => $unitBdt,
                'list_currency' => $currency,
                'list_unit_price' => $price,
                'status' => 'active',
            ]);
        });
    }

    /** পোস্ট বাতিল: অবশিষ্ট শেয়ারের reserve ছেড়ে দেওয়া */
    public function cancel(User $seller, ShareListing $listing): void
    {
        DB::transaction(function () use ($seller, $listing) {
            $l = ShareListing::whereKey($listing->id)->lockForUpdate()->firstOrFail();

            if ($l->seller_id !== $seller->id) {
                throw new ShareException('এই পোস্টটি আপনার নয়।');
            }

            if ($l->status !== 'active') {
                throw new ShareException('এই পোস্ট আগেই বন্ধ হয়েছে।');
            }

            $holding = ShareHolding::where('user_id', $seller->id)
                ->where('project_id', $l->project_id)
                ->lockForUpdate()
                ->first();

            if ($holding) {
                $holding->forceFill([
                    'listed_shares' => max(0, (int) $holding->listed_shares - (int) $l->remaining_shares),
                ])->save();
            }

            $l->update(['status' => 'cancelled', 'closed_at' => now()]);
        });
    }

    /* ================================================================ */
    /*  Buy (কোনো কনফার্ম নেই, ব্যালেন্স থাকলেই কেনা যায়)               */
    /* ================================================================ */

    public function buy(User $buyer, ShareListing $listing, int $qty): ShareTrade
    {
        if ($qty < 1) {
            throw new ShareException('শেয়ারের সংখ্যা কমপক্ষে ১ হতে হবে।');
        }

        if ($buyer->role !== 'user' || $buyer->status !== 'active') {
            throw new ShareException('আপনার অ্যাকাউন্ট দিয়ে শেয়ার কেনা যাবে না।');
        }

        $buyerHome = $this->shares->home($buyer);

        if (! $buyerHome) {
            throw new ShareException('আপনার Wallet কারেন্সির রেট সেট নেই।');
        }

        return DB::transaction(function () use ($buyer, $listing, $qty, $buyerHome) {
            // লক ক্রম: Listing -> Holdings (user id অনুযায়ী) -> Wallets (id অনুযায়ী), যাতে deadlock না হয়
            $l = ShareListing::with('project')->whereKey($listing->id)->lockForUpdate()->firstOrFail();

            if ($l->status !== 'active' || $l->remaining_shares < 1) {
                throw new ShareException('এই অফারটি আর উপলব্ধ নেই।');
            }

            if ($l->seller_id === $buyer->id) {
                throw new ShareException('নিজের শেয়ার নিজে কেনা যাবে না।');
            }

            if ($qty > $l->remaining_shares) {
                throw new ShareException("মাত্র {$l->remaining_shares}টি শেয়ার অবশিষ্ট আছে।");
            }

            $this->assertTradable($l->project);

            $seller = User::findOrFail($l->seller_id);
            $sellerHome = $this->shares->home($seller);

            if (! $sellerHome) {
                throw new ShareException('বিক্রেতার কারেন্সির রেট পাওয়া যাচ্ছে না, এখন কেনা যাবে না।');
            }

            ShareHolding::firstOrCreate(
                ['user_id' => $buyer->id, 'project_id' => $l->project_id],
                ['shares' => 0, 'locked_shares' => 0, 'invested_bdt' => 0, 'status' => 'active']
            );

            $holdings = ShareHolding::where('project_id', $l->project_id)
                ->whereIn('user_id', [$l->seller_id, $buyer->id])
                ->orderBy('user_id')
                ->lockForUpdate()
                ->get()
                ->keyBy('user_id');

            $sh = $holdings->get($l->seller_id);
            $bh = $holdings->get($buyer->id);

            if (! $sh || ! $bh) {
                throw new ShareException('শেয়ারের তথ্য পাওয়া যায়নি।');
            }

            // বিক্রেতার শেয়ার সত্যিই আছে কিনা এবং Position-এ Lock হয়ে যায়নি তো
            if (($sh->shares - $sh->locked_shares) < $qty || (int) $sh->listed_shares < $qty) {
                throw new ShareException('বিক্রেতার শেয়ার এখন উপলব্ধ নেই।');
            }

            // ---- হিসাব ----
            $unit = (string) $l->unit_price_bdt;
            $totalBdt = $this->ceil2(bcmul($unit, (string) $qty, 6));
            $feePercent = $this->feePercent();
            $feeBdt = bcdiv(bcmul($totalBdt, $feePercent, 4), '100', 2);
            $netBdt = bcsub($totalBdt, $feeBdt, 2);

            $buyerAmount = $this->ceil2(bcdiv($totalBdt, $buyerHome['rate'], 8));           // ক্রেতার কাটা (উপরে রাউন্ড)
            $sellerAmount = bcadd(bcdiv($netBdt, $sellerHome['rate'], 8), '0', 2);          // বিক্রেতার পাওনা (নিচে রাউন্ড)

            if (bccomp($buyerAmount, '0', 2) <= 0 || bccomp($sellerAmount, '0', 2) <= 0) {
                throw new ShareException('এই পরিমাণ লেনদেন করা যাবে না।');
            }

            // বিক্রি হওয়া অংশের গড় খরচ (লাভ/ক্ষতির জন্য)
            $invested = (string) $sh->invested_bdt;
            $costRemoved = $qty === (int) $sh->shares
                ? $invested
                : bcdiv(bcmul($invested, (string) $qty, 4), (string) $sh->shares, 2);

            $profit = bcsub($netBdt, $costRemoved, 2);

            // দুই Wallet আগেই id অনুযায়ী লক করে নেওয়া (opposite দিকের দুই লেনদেনে deadlock এড়াতে)
            $bw = Wallet::firstOrCreate(
                ['user_id' => $buyer->id, 'currency' => $buyerHome['currency']],
                ['balance' => 0, 'withdrawable_balance' => 0]
            );
            $sw = Wallet::firstOrCreate(
                ['user_id' => $seller->id, 'currency' => $sellerHome['currency']],
                ['balance' => 0, 'withdrawable_balance' => 0]
            );
            Wallet::whereIn('id', [$bw->id, $sw->id])->orderBy('id')->lockForUpdate()->get();

            $trade = ShareTrade::create([
                'listing_id' => $l->id,
                'project_id' => $l->project_id,
                'seller_id' => $seller->id,
                'buyer_id' => $buyer->id,
                'shares' => $qty,
                'unit_price_bdt' => $unit,
                'total_bdt' => $totalBdt,
                'fee_percent' => $feePercent,
                'fee_bdt' => $feeBdt,
                'seller_net_bdt' => $netBdt,
                'buyer_currency' => $buyerHome['currency'],
                'buyer_rate' => $buyerHome['rate'],
                'buyer_amount' => $buyerAmount,
                'seller_currency' => $sellerHome['currency'],
                'seller_rate' => $sellerHome['rate'],
                'seller_amount' => $sellerAmount,
                'seller_profit_loss_bdt' => $profit,
            ]);

            $name = $l->project->name;

            // ক্রেতার Wallet থেকে কাটা
            try {
                $this->wallets->debit(
                    $buyer,
                    'p2p_buy',
                    $buyerAmount,
                    $trade,
                    "Bought {$qty} share(s) of {$name} (marketplace)",
                    ['project_id' => $l->project_id, 'shares' => $qty, 'trade_id' => $trade->id],
                    $buyerHome['currency']
                );
            } catch (RuntimeException $e) {
                throw new ShareException('Wallet-এ পর্যাপ্ত ব্যালেন্স নেই।');
            }

            // বিক্রেতার Wallet-এ যোগ (কমিশন বাদে)
            // ⚠️ আগের 'share_sell'-এর মতোই এই টাকা তোলা যাবে না, শুধু শেয়ার কেনায় ব্যবহার হবে।
            //    তোলার মতো করতে চাইলে নিচের meta-তে 'withdrawable' => true যোগ করুন।
            $this->wallets->credit(
                $seller,
                'p2p_sell',
                $sellerAmount,
                $trade,
                "Sold {$qty} share(s) of {$name} (marketplace)",
                ['project_id' => $l->project_id, 'shares' => $qty, 'trade_id' => $trade->id],
                $sellerHome['currency']
            );

            // শেয়ার সরানো
            $sellerRemaining = $sh->shares - $qty;

            $sh->forceFill([
                'shares' => $sellerRemaining,
                'listed_shares' => (int) $sh->listed_shares - $qty,
                'invested_bdt' => $sellerRemaining === 0 ? '0.00' : bcsub($invested, $costRemoved, 2),
                'status' => $sellerRemaining === 0 ? 'completed' : 'active',
            ])->save();

            $bh->forceFill([
                'shares' => $bh->shares + $qty,
                'invested_bdt' => bcadd((string) $bh->invested_bdt, $totalBdt, 2),
                'status' => 'active',
            ])->save();

            // পোস্ট হালনাগাদ
            $left = $l->remaining_shares - $qty;

            $l->update([
                'remaining_shares' => $left,
                'status' => $left === 0 ? 'sold' : 'active',
                'closed_at' => $left === 0 ? now() : null,
            ]);

            // Purchase/Sales History, Profit-Loss ও Leaderboard-এর জন্য দুই পক্ষের ShareOrder
            $unit2 = bcadd($unit, '0', 2);

            $this->makeOrder($buyer->id, $l->project_id, 'buy', $qty, $unit2, $totalBdt, '0.00', $totalBdt,
                $buyerHome['currency'], $buyerHome['rate'], $buyerAmount, null, $trade->id);

            $this->makeOrder($seller->id, $l->project_id, 'sell', $qty, $unit2, $totalBdt, $feeBdt, $netBdt,
                $sellerHome['currency'], $sellerHome['rate'], $sellerAmount, $profit, $trade->id);

            return $trade;
        });
    }

    /* ------------------------------------------------------------------ */

    private function makeOrder(
        int $userId, int $projectId, string $type, int $qty, string $unit, string $total,
        string $fee, string $net, string $currency, string $rate, string $walletAmount,
        ?string $profit, int $tradeId
    ): void {
        $attrs = [
            'user_id' => $userId,
            'project_id' => $projectId,
            'type' => $type,
            'shares' => $qty,
            'unit_price_bdt' => $unit,
            'total_bdt' => $total,
            'fee_bdt' => $fee,
            'net_bdt' => $net,
            'wallet_currency' => $currency,
            'exchange_rate' => $rate,
            'wallet_amount' => $walletAmount,
            'status' => 'completed',
        ];

        if ($profit !== null) {
            $attrs['profit_loss_bdt'] = $profit;
        }

        $order = new ShareOrder($attrs);
        $order->share_trade_id = $tradeId;
        $order->save();
    }

    private function assertTradable(Project $p): void
    {
        if (! $p->is_active || $p->status === 'paused') {
            throw new ShareException('এই প্রজেক্টে এখন শেয়ার লেনদেন বন্ধ আছে।');
        }
    }

    /** ২ দশমিকে উপরে রাউন্ড (bcmath) */
    private function ceil2(string $value): string
    {
        $cents = bcmul($value, '100', 8);
        $int = bcadd($cents, '0', 0);

        if (bccomp($cents, $int, 8) > 0) {
            $int = bcadd($int, '1', 0);
        }

        return bcdiv($int, '100', 2);
    }
}