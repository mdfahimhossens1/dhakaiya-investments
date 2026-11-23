<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ShareListing;
use App\Models\ShareTrade;
use App\Services\ShareException;
use App\Services\ShareMarketplaceService;
use App\Services\ShareService;
use App\Support\Currency;
use Illuminate\Http\Request;

class ResellController extends Controller
{
    /** Resell Share: Post for Sale ফর্ম + আমার পোস্টগুলো */
    public function index(Request $request, ShareMarketplaceService $market, ShareService $shares)
    {
        $user = auth()->user();
        $currency = strtoupper($user->walletCurrency());

        $holdings = $user->shareHoldings()
            ->where('shares', '>', 0)
            ->with('project')
            ->get()
            ->map(function ($h) use ($shares, $currency) {
                $h->sellable = max(0, $h->shares - $h->locked_shares - (int) $h->listed_shares);
                $h->company_price_local = $shares->fromBdt((string) $h->project->share_price, $currency);

                return $h;
            })
            ->filter(fn ($h) => $h->sellable > 0)
            ->values();

        $myListings = ShareListing::with('project')
            ->where('seller_id', $user->id)
            ->latest()
            ->paginate(10);

        $summary = [
            'active_listings' => ShareListing::where('seller_id', $user->id)->where('status', 'active')->count(),
            'listed_shares' => (int) ShareListing::where('seller_id', $user->id)->where('status', 'active')->sum('remaining_shares'),
            'trades_sold' => ShareTrade::where('seller_id', $user->id)->count(),
            'total_received' => (string) ShareTrade::where('seller_id', $user->id)
                ->where('seller_currency', $currency)->sum('seller_amount'),
        ];

        return view('user.resell.index', [
            'user' => $user,
            'currency' => $currency,
            'symbol' => Currency::symbol($currency),
            'holdings' => $holdings,
            'myListings' => $myListings,
            'summary' => $summary,
            'feePercent' => $market->feePercent(),
            'selectedProject' => $request->integer('project'),
        ]);
    }

    /** Post for Sale */
    public function store(Request $request, ShareMarketplaceService $market)
    {
        $user = $request->user();

        $data = $request->validate([
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'shares' => ['required', 'integer', 'min:1', 'max:1000000'],
            'price' => ['required', 'numeric', 'gt:0', 'max:100000000', 'decimal:0,2'],
        ]);

        try {
            $listing = $market->post(
                $user,
                Project::findOrFail($data['project_id']),
                (int) $data['shares'],
                (string) $data['price'],
                $user->walletCurrency()   // দাম সবসময় বিক্রেতার নিজের Wallet কারেন্সিতে
            );
        } catch (ShareException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('user.resell.index')
            ->with('success', "{$listing->shares}টি শেয়ার মার্কেটপ্লেসে পোস্ট হয়েছে।");
    }

    public function cancel(Request $request, ShareListing $listing, ShareMarketplaceService $market)
    {
        try {
            $market->cancel($request->user(), $listing);
        } catch (ShareException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'পোস্ট বাতিল হয়েছে, শেয়ার আবার আপনার কাছে ফ্রি।');
    }

    /** আমার কেনা-বেচার ইতিহাস (মার্কেটপ্লেস) */
    public function trades()
    {
        $user = auth()->user();

        $trades = ShareTrade::with(['project:id,name', 'seller:id,name', 'buyer:id,name'])
            ->where(fn ($q) => $q->where('seller_id', $user->id)->orWhere('buyer_id', $user->id))
            ->latest()
            ->paginate(15);

        return view('user.resell.trades', compact('user', 'trades'));
    }
}