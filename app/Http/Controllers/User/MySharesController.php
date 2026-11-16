<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\ShareException;
use App\Services\ShareService;
use Illuminate\Http\Request;

class MySharesController extends Controller
{
    public function index(Request $request, ShareService $service)
    {
        $user = auth()->user();

        $status = $request->query('status', 'all');

        $allowedStatuses = [
            'all',
            'active',
            'pending',
            'completed',
        ];

        if (!in_array($status, $allowedStatuses, true)) {
            $status = 'all';
        }

        $base = $user->shareHoldings();

        $shares = $user->shareHoldings()
            ->with(['project.company', 'project.projectCategory'])
            ->when($status === 'active', fn ($q) => $q->where('shares', '>', 0))
            ->when($status === 'completed', fn ($q) => $q->where('shares', 0))
            ->when($status === 'pending', fn ($q) => $q->whereRaw('1 = 0')) // শেয়ার কেনা তাৎক্ষণিক, pending নেই
            ->latest('updated_at')
            ->paginate(12)
            ->withQueryString();

        $active = (clone $base)->where('shares', '>', 0)->with('project')->get();

        $summary = [
            'total_records' => $user->shareHoldings()->count(),
            'active_records' => $active->count(),
            'pending_records' => 0,
            'completed_records' => $user->shareHoldings()->where('shares', 0)->count(),
            'total_shares' => $active->sum('shares'),
            'locked_shares' => $active->sum('locked_shares'),
            'total_invested_bdt' => $active->reduce(
                fn ($c, $h) => bcadd($c, (string) $h->invested_bdt, 2), '0.00'
            ),
            'current_value_bdt' => $active->reduce(
                fn ($c, $h) => bcadd($c, $h->current_value, 2), '0.00'
            ),
        ];

        // রিসেলে টাকা কোন Wallet-এ যাবে (rate সেট আছে এমন Wallet)
        $walletOptions = $user->wallets()->get()
            ->filter(fn ($w) => $service->rate($w->currency) !== null)
            ->map(fn ($w) => [
                'currency' => $w->currency,
                'balance' => $w->balance,
                'rate' => $service->rate($w->currency),
            ])
            ->values();

        return view('user.investment.my-shares', compact(
            'user',
            'shares',
            'status',
            'summary',
            'walletOptions'
        ));
    }

    public function sell(Request $request, Project $project, ShareService $service)
    {
        $data = $request->validate([
            'shares' => ['required', 'integer', 'min:1', 'max:1000000'],
            'currency' => ['required', 'string', 'size:3'],
        ]);

        try {
            $order = $service->sell(
                $request->user(),
                $project,
                (int) $data['shares'],
                $data['currency']
            );
        } catch (ShareException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('user.sales-history.index')
            ->with('success', "{$order->shares}টি শেয়ার রিসেল হয়েছে, {$order->wallet_amount} {$order->wallet_currency} Wallet-এ যোগ হয়েছে।");
    }
}