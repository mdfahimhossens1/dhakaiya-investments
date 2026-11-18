<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncomeHistoryController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = $user->dailyProfitLogs();

        // ---- Filter: date range ----
        if ($request->filled('from')) {
            $query->whereDate('profit_date', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('profit_date', '<=', $request->to);
        }

        // ---- Filter: currency ----
        if ($request->filled('currency')) {
            $query->where('wallet_currency', $request->currency);
        }

        // ---- Filter: search (project/note) ----
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('note', 'like', "%{$q}%")
                    ->orWhere('wallet_currency', 'like', "%{$q}%");
            });
        }

        // ---- Summary stats (filtered) ----
        $totalIncome   = (clone $query)->sum('profit_bdt');
        $thisMonth     = (clone $query)
            ->whereYear('profit_date', now()->year)
            ->whereMonth('profit_date', now()->month)
            ->sum('profit_bdt');
        $lastDate      = (clone $query)->max('profit_date');
        $lastIncome    = $lastDate
            ? (clone $query)->whereDate('profit_date', $lastDate)->sum('profit_bdt')
            : 0;
        $recordsCount  = (clone $query)->count();

        // ---- Currency breakdown ----
        $byCurrency = (clone $query)
            ->select('wallet_currency', DB::raw('SUM(wallet_amount) as total'), DB::raw('SUM(profit_bdt) as bdt'))
            ->groupBy('wallet_currency')
            ->get();

        // ---- Paginated records ----
        $incomes = $query->latest('profit_date')->paginate(20)->withQueryString();

        // ---- Available currencies (for dropdown) ----
        $currencies = $user->dailyProfitLogs()
            ->select('wallet_currency')->distinct()->pluck('wallet_currency');

        return view('user.income-history.index', compact(
            'incomes', 'totalIncome', 'thisMonth', 'lastDate', 'lastIncome',
            'recordsCount', 'byCurrency', 'currencies'
        ));
    }
}