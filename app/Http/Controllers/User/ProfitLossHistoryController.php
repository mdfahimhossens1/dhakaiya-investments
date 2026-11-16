<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfitLossHistoryController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $type = $request->query('type', 'all');

        if (!in_array($type, ['all', 'profit', 'loss'], true)) {
            $type = 'all';
        }

        // ১) প্রতিদিনের প্রফিট (Wallet-এ জমা হওয়া)
        $dailyRows = $user->dailyProfitLogs()
            ->with('project:id,name')
            ->latest('profit_date')
            ->limit(200)
            ->get()
            ->map(fn ($l) => (object) [
                'id' => 'D' . $l->id,
                'project_name' => $l->project->name ?? '—',
                'record_date' => \Illuminate\Support\Carbon::parse($l->profit_date)->format('d M Y'),
                'sort' => \Illuminate\Support\Carbon::parse($l->profit_date)->timestamp,
                'type' => 'profit',
                'description' => 'Daily profit',
                'amount' => (float) $l->profit_bdt,
            ]);

        // ২) শেয়ার রিসেল করে লাভ/ক্ষতি
        $resaleRows = $user->shareOrders()
            ->where('type', 'sell')
            ->where('status', '!=', 'rejected')
            ->with('project:id,name')
            ->latest()
            ->limit(200)
            ->get()
            ->map(fn ($o) => (object) [
                'id' => 'S' . $o->id,
                'project_name' => $o->project->name ?? '—',
                'record_date' => $o->created_at->format('d M Y'),
                'sort' => $o->created_at->timestamp,
                'type' => (float) $o->profit_loss_bdt >= 0 ? 'profit' : 'loss',
                'description' => 'Resale of ' . $o->shares . ' share(s)',
                'amount' => abs((float) $o->profit_loss_bdt),
            ]);

        $all = $dailyRows->concat($resaleRows)->sortByDesc('sort')->values();

        $records = $type === 'all' ? $all : $all->where('type', $type)->values();

        // সারাংশ সব রেকর্ড থেকে (ফিল্টারের উপর নির্ভর করে না)
        $totalProfit = (float) $user->dailyProfitLogs()->sum('profit_bdt')
            + (float) $user->shareOrders()->where('type', 'sell')->where('status', '!=', 'rejected')
                ->where('profit_loss_bdt', '>', 0)->sum('profit_loss_bdt');

        $totalLoss = abs((float) $user->shareOrders()->where('type', 'sell')->where('status', '!=', 'rejected')
            ->where('profit_loss_bdt', '<', 0)->sum('profit_loss_bdt'));

        $summary = [
            'total_profit' => $totalProfit,
            'total_loss' => $totalLoss,
            'net_result' => $totalProfit - $totalLoss,
            'total_records' => $all->count(),
            'profit_records' => $all->where('type', 'profit')->count(),
            'loss_records' => $all->where('type', 'loss')->count(),
        ];

        return view('user.investment.profit-loss-history', compact(
            'user',
            'records',
            'summary',
            'type'
        ));
    }
}