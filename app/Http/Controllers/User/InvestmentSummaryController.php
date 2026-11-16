<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InvestmentSummaryController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $holdings = $user->shareHoldings()->with('project')->get();
        $active = $holdings->where('shares', '>', 0);

        $buyOrders = $user->shareOrders()
            ->where('type', 'buy')
            ->with('project')
            ->latest();

        $dailyProfit = (float) $user->dailyProfitLogs()->sum('profit_bdt');
        $resaleProfit = (float) $user->shareOrders()
            ->where('type', 'sell')
            ->where('status', '!=', 'rejected')
            ->sum('profit_loss_bdt');

        $summary = [
            'total_projects' => $active->count(),
            'total_shares' => $active->sum('shares'),
            'total_invested' => $active->sum(fn ($h) => (float) $h->invested_bdt),
            'total_profit' => $dailyProfit + $resaleProfit,
            'active_investments' => $active->count(),
            'completed_investments' => $holdings->where('shares', 0)->count(),
            'pending_investments' => $user->shareOrders()
                ->where('type', 'buy')->where('status', 'pending')->count(),
        ];

        $investments = (clone $buyOrders)->limit(20)->get()->map(fn ($o) => (object) [
            'id' => $o->id,
            'project_name' => $o->project->name ?? 'Removed project',
            'investment_date' => $o->created_at->format('d M Y'),
            'quantity' => $o->shares,
            'amount' => (float) $o->net_bdt,
            'status' => $o->status,
        ]);

        $projectBreakdown = $active->map(fn ($h) => (object) [
            'name' => $h->project->name ?? 'Removed project',
            'amount' => (float) $h->invested_bdt,
        ])->sortByDesc('amount')->values();

        return view('user.investment.investment-summary', compact(
            'user',
            'summary',
            'investments',
            'projectBreakdown'
        ));
    }
}