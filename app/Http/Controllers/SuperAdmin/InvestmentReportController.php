<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\DailyProfitLog;
use App\Models\Project;
use App\Models\ShareHolding;
use App\Models\ShareOrder;
use Illuminate\Http\Request;

class InvestmentReportController extends Controller
{
    /* ---------------- Investment Summary ---------------- */
    public function summary()
    {
        $holdings = ShareHolding::where('shares', '>', 0)->with('project')->get();

        $invested = $holdings->reduce(fn ($c, $h) => bcadd($c, (string) $h->invested_bdt, 2), '0.00');
        $current = $holdings->reduce(fn ($c, $h) => bcadd($c, (string) $h->current_value, 2), '0.00');

        $orders = fn (string $type) => ShareOrder::where('type', $type)->where('status', '!=', 'rejected');

        $latestProfitDate = DailyProfitLog::max('profit_date');

        $summary = [
            'investors' => $holdings->pluck('user_id')->unique()->count(),
            'invested' => $invested,
            'current_value' => $current,
            'unrealized' => bcsub($current, $invested, 2),
            'resale_pl' => (string) $orders('sell')->sum('profit_loss_bdt'),
            'shares_held' => $holdings->sum('shares'),
            'buy_count' => $orders('buy')->count(),
            'buy_volume' => $orders('buy')->sum('net_bdt'),
            'sell_count' => $orders('sell')->count(),
            'sell_volume' => $orders('sell')->sum('net_bdt'),
            'pending_orders' => ShareOrder::where('status', 'pending')->count(),
            'profit_paid_total' => DailyProfitLog::sum('profit_bdt'),
            'profit_paid_month' => DailyProfitLog::whereYear('profit_date', now()->year)
                ->whereMonth('profit_date', now()->month)
                ->sum('profit_bdt'),
            'latest_profit_date' => $latestProfitDate,
            'latest_profit_amount' => $latestProfitDate
                ? DailyProfitLog::whereDate('profit_date', $latestProfitDate)->sum('profit_bdt')
                : 0,
        ];

        $topProjects = $holdings->groupBy('project_id')->map(fn ($g) => [
            'name' => $g->first()->project->name ?? 'Removed project',
            'holders' => $g->pluck('user_id')->unique()->count(),
            'shares' => $g->sum('shares'),
            'invested' => $g->sum(fn ($h) => (float) $h->invested_bdt),
            'current' => $g->sum(fn ($h) => (float) $h->current_value),
        ])->sortByDesc('invested')->take(5)->values();

        $recent = ShareOrder::with(['user', 'project'])->latest()->limit(10)->get();

        return view('super-admin.investments.summary', compact('summary', 'topProjects', 'recent'));
    }

    /* ---------------- Project-wise Records ---------------- */
    public function projectWise(Request $request)
    {
        $search = $request->query('search');

        $projects = Project::with('company')
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->orderBy('sort_order')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $ids = $projects->pluck('id');

        $holdings = ShareHolding::with('project')
            ->whereIn('project_id', $ids)
            ->where('shares', '>', 0)
            ->get()
            ->groupBy('project_id');

        $agg = ShareOrder::whereIn('project_id', $ids)
            ->where('status', '!=', 'rejected')
            ->selectRaw('project_id, type, SUM(shares) as shares, SUM(net_bdt) as net, SUM(profit_loss_bdt) as pl')
            ->groupBy('project_id', 'type')
            ->get()
            ->groupBy('project_id');

        $profitPaid = DailyProfitLog::whereIn('project_id', $ids)
            ->selectRaw('project_id, SUM(profit_bdt) as total')
            ->groupBy('project_id')
            ->pluck('total', 'project_id');

        $projects->getCollection()->transform(function ($p) use ($holdings, $agg, $profitPaid) {
            $h = $holdings->get($p->id, collect());
            $a = $agg->get($p->id, collect());
            $buy = $a->firstWhere('type', 'buy');
            $sell = $a->firstWhere('type', 'sell');

            $p->stats = (object) [
                'holders' => $h->pluck('user_id')->unique()->count(),
                'held' => $h->sum('shares'),
                'invested' => $h->sum(fn ($x) => (float) $x->invested_bdt),
                'current' => $h->sum(fn ($x) => (float) $x->current_value),
                'bought' => (int) ($buy->shares ?? 0),
                'buy_volume' => (float) ($buy->net ?? 0),
                'sold' => (int) ($sell->shares ?? 0),
                'resale_pl' => (float) ($sell->pl ?? 0),
                'profit_paid' => (float) ($profitPaid[$p->id] ?? 0),
            ];

            return $p;
        });

        return view('super-admin.investments.project-wise', compact('projects', 'search'));
    }

    /* ---------------- Profit & Loss History ---------------- */
    public function profitLoss(Request $request)
    {
        $result = $request->query('result', 'all');
        if (!in_array($result, ['all', 'profit', 'loss'], true)) {
            $result = 'all';
        }

        $search = $request->query('search');
        $from = $request->query('from');
        $to = $request->query('to');

        $userSearch = fn ($q) => $q->whereHas('user', fn ($u) => $u
            ->where('name', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%"));

        // Daily profit logs
        $logs = DailyProfitLog::with(['user', 'project'])
            ->when($from, fn ($q) => $q->whereDate('profit_date', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('profit_date', '<=', $to))
            ->when($search, $userSearch)
            ->latest('profit_date')
            ->paginate(20, ['*'], 'profits_page')
            ->withQueryString();

        // Resale profit / loss
        $base = fn () => ShareOrder::where('type', 'sell')->where('status', '!=', 'rejected');

        $sales = $base()
            ->with(['user', 'project'])
            ->when($result === 'profit', fn ($q) => $q->where('profit_loss_bdt', '>', 0))
            ->when($result === 'loss', fn ($q) => $q->where('profit_loss_bdt', '<', 0))
            ->when($search, $userSearch)
            ->latest()
            ->paginate(15, ['*'], 'sales_page')
            ->withQueryString();

        $latestDate = DailyProfitLog::max('profit_date');

        $summary = [
            'profit_total' => DailyProfitLog::sum('profit_bdt'),
            'profit_month' => DailyProfitLog::whereYear('profit_date', now()->year)
                ->whereMonth('profit_date', now()->month)
                ->sum('profit_bdt'),
            'latest_date' => $latestDate,
            'latest_amount' => $latestDate ? DailyProfitLog::whereDate('profit_date', $latestDate)->sum('profit_bdt') : 0,
            'log_count' => DailyProfitLog::count(),
            'by_currency' => DailyProfitLog::selectRaw('wallet_currency, SUM(wallet_amount) as total')
                ->groupBy('wallet_currency')
                ->pluck('total', 'wallet_currency'),
            'resale_net' => $base()->sum('profit_loss_bdt'),
            'gross_profit' => $base()->where('profit_loss_bdt', '>', 0)->sum('profit_loss_bdt'),
            'gross_loss' => $base()->where('profit_loss_bdt', '<', 0)->sum('profit_loss_bdt'),
            'profit_trades' => $base()->where('profit_loss_bdt', '>', 0)->count(),
            'loss_trades' => $base()->where('profit_loss_bdt', '<', 0)->count(),
        ];

        return view('super-admin.investments.profit-loss', compact(
            'logs', 'sales', 'summary', 'result', 'search', 'from', 'to'
        ));
    }

    /* ---------------- Maturity Information ---------------- */
    public function maturity(Request $request)
    {
        $state = $request->query('state', 'all');
        if (!in_array($state, ['all', 'running', 'matured', 'no_date'], true)) {
            $state = 'all';
        }

        $today = today();

        $projects = Project::with('company')
            ->when($state === 'running', fn ($q) => $q->whereDate('end_date', '>', $today))
            ->when($state === 'matured', fn ($q) => $q->whereDate('end_date', '<=', $today))
            ->when($state === 'no_date', fn ($q) => $q->whereNull('end_date'))
            ->orderByRaw('end_date IS NULL')
            ->orderBy('end_date')
            ->paginate(15)
            ->withQueryString();

        $holdings = ShareHolding::with('project')
            ->whereIn('project_id', $projects->pluck('id'))
            ->where('shares', '>', 0)
            ->get()
            ->groupBy('project_id');

        $projects->getCollection()->transform(function ($p) use ($holdings, $today) {
            $h = $holdings->get($p->id, collect());
            $end = $p->end_date;
            $days = $end ? (int) $today->diffInDays($end->copy()->startOfDay(), false) : null;

            $p->maturity = (object) [
                'state' => !$end ? 'no_date' : ($days <= 0 ? 'matured' : 'running'),
                'days_left' => $days,
                'holders' => $h->pluck('user_id')->unique()->count(),
                'shares' => $h->sum('shares'),
                'invested' => $h->sum(fn ($x) => (float) $x->invested_bdt),
            ];

            return $p;
        });

        $summary = [
            'total' => Project::count(),
            'running' => Project::whereDate('end_date', '>', $today)->count(),
            'matured' => Project::whereDate('end_date', '<=', $today)->count(),
            'no_date' => Project::whereNull('end_date')->count(),
        ];

        return view('super-admin.investments.maturity', compact('projects', 'state', 'summary'));
    }
}