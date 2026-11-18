<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PositionRank;
use App\Models\UserPosition;
use App\Services\ShareService;
use Illuminate\Http\Request;

class ProfitLossHistoryController extends Controller
{
    public function index(Request $request, ShareService $shares)
    {
        $user = auth()->user();

        $profits = $user->dailyProfitLogs()
            ->with('project:id,name,slug')
            ->when($request->filled('from'), fn ($q) => $q->whereDate('profit_date', '>=', $request->query('from')))
            ->when($request->filled('to'), fn ($q) => $q->whereDate('profit_date', '<=', $request->query('to')))
            ->latest('profit_date')
            ->paginate(10)
            ->withQueryString();

        $totalByCurrency = $user->dailyProfitLogs()
            ->selectRaw('wallet_currency, SUM(wallet_amount) as total')
            ->groupBy('wallet_currency')
            ->pluck('total', 'wallet_currency');

        // এখনকার শেয়ার থেকে প্রতিদিন কত আসবে (পরের রাতে যোগ হবে, শুধু যে শেয়ার পুরো দিন ধরা থাকবে)
        $boost = '0';
        $rankId = UserPosition::where('user_id', $user->id)->value('position_rank_id');

        if ($rankId) {
            $boost = (string) (PositionRank::where('id', $rankId)->where('status', true)->value('profit_boost_percent') ?? 0);
        }

        $expectedRows = [];
        $expected = '0.00';

        foreach ($user->shareHoldings()->where('shares', '>', 0)->with('project')->get() as $h) {
            $p = $h->project;

            if (! $p || ! $p->is_active || $p->status !== 'active' || bccomp((string) $p->daily_profit_percent, '0', 3) <= 0) {
                continue;
            }

            $daily = bcdiv(bcmul(bcmul((string) $p->share_price, (string) $h->shares, 2), (string) $p->daily_profit_percent, 6), '100', 2);

            if (bccomp($boost, '0', 2) > 0) {
                $daily = bcdiv(bcmul($daily, bcadd('100', $boost, 2), 6), '100', 2);
            }

            $expected = bcadd($expected, $daily, 2);
            $expectedRows[] = (object) [
                'project' => $p->name,
                'shares' => $h->shares,
                'percent' => $p->daily_profit_percent,
                'daily_bdt' => $daily,
                'since' => $h->updated_at,
            ];
        }

        $home = $shares->home($user);
        $currency = $user->walletCurrency();
        $expectedLocal = $home ? bcdiv($expected, $home['rate'], 2) : null;

        $summary = [
            'total_profit_bdt' => $user->dailyProfitLogs()->sum('profit_bdt'),
            'this_month_bdt' => $user->dailyProfitLogs()
                ->whereYear('profit_date', now()->year)
                ->whereMonth('profit_date', now()->month)
                ->sum('profit_bdt'),
            'last_profit_date' => $user->dailyProfitLogs()->max('profit_date'),
            'last_profit_bdt' => $user->dailyProfitLogs()
                ->whereDate('profit_date', $user->dailyProfitLogs()->max('profit_date'))->sum('profit_bdt'),
            'resale_profit_loss_bdt' => $user->shareOrders()->where('type', 'sell')->sum('profit_loss_bdt'),
            'by_currency' => $totalByCurrency,
        ];

        return view('user.investment.profit-loss-history', compact(
            'user', 'profits', 'summary', 'totalByCurrency',
            'expected', 'expectedLocal', 'expectedRows', 'currency', 'boost'
        ));
    }
}