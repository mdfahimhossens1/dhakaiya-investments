<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ShareListing;
use App\Models\Wallet;
use App\Services\ShareException;
use App\Services\ShareMarketplaceService;
use App\Services\ShareService;
use App\Support\Currency;
use App\Support\Notify;
use Illuminate\Http\Request;

class MarketplaceController extends Controller
{
    public function index(Request $request, ShareService $shares, ShareMarketplaceService $market)
    {
        $user = auth()->user();
        $currency = strtoupper($user->walletCurrency());
        $rate = $shares->rate($currency);

        $sort = in_array($request->query('sort'), ['new', 'low', 'high'], true) ? $request->query('sort') : 'new';
        $projectId = $request->integer('project');

        $listings = ShareListing::with(['project.company', 'seller:id,name'])
            ->where('status', 'active')
            ->where('remaining_shares', '>', 0)
            ->when($projectId, fn ($q) => $q->where('project_id', $projectId))
            ->when($sort === 'low', fn ($q) => $q->orderBy('unit_price_bdt'))
            ->when($sort === 'high', fn ($q) => $q->orderByDesc('unit_price_bdt'))
            ->when($sort === 'new', fn ($q) => $q->latest())
            ->paginate(12)
            ->withQueryString();

        // প্রতিটি পোস্টের দাম ক্রেতার নিজের কারেন্সিতে
        $listings->getCollection()->transform(function ($l) use ($market, $shares, $currency) {
            $l->unit_price_local = $market->unitPriceIn($l, $currency);
            $l->company_price_local = $shares->unitPriceIn($l->project, $currency);

            return $l;
        });

        $projects = Project::where('is_active', true)->orderBy('name')->get(['id', 'name']);

        $balance = (string) (Wallet::where('user_id', $user->id)->where('currency', $currency)->value('balance') ?? 0);

        return view('user.marketplace.index', [
            'user' => $user,
            'listings' => $listings,
            'projects' => $projects,
            'projectId' => $projectId,
            'sort' => $sort,
            'currency' => $currency,
            'symbol' => Currency::symbol($currency),
            'rate' => $rate,
            'balance' => $balance,
        ]);
    }

    /** Buy: কোনো কনফার্ম নেই, Wallet-এ টাকা থাকলেই কেনা যায় */
    public function buy(Request $request, ShareListing $listing, ShareMarketplaceService $market)
    {
        $data = $request->validate([
            'shares' => ['required', 'integer', 'min:1', 'max:1000000'],
        ]);

        try {
            $trade = $market->buy($request->user(), $listing, (int) $data['shares']);
        } catch (ShareException $e) {
            return back()->with('error', $e->getMessage());
        }

        // Transaction commit হওয়ার পর notification
        $trade->load(['project:id,name', 'seller']);

        if ($trade->seller) {
            Notify::user(
                $trade->seller,
                'Shares Sold',
                "{$trade->shares} share(s) of {$trade->project->name} were bought from your marketplace post. "
                    . Notify::money($trade->seller_amount, $trade->seller_currency) . ' has been added to your wallet.',
                'success'
            );
        }

        return redirect()->route('user.resell.trades')->with(
            'success',
            "{$trade->shares}টি শেয়ার কেনা হয়েছে, Wallet থেকে {$trade->buyer_amount} {$trade->buyer_currency} কাটা হয়েছে।"
        );
    }
}