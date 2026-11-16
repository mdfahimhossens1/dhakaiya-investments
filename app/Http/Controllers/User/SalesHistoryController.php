<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SalesHistoryController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $status = $request->query('status', 'all');

        $allowedStatuses = [
            'all',
            'pending',
            'approved',
            'rejected',
            'completed',
        ];

        if (!in_array($status, $allowedStatuses, true)) {
            $status = 'all';
        }

        $sales = $user->shareOrders()
            ->where('type', 'sell')
            ->with(['project.company'])
            ->when($status !== 'all', fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $counts = $user->shareOrders()
            ->where('type', 'sell')
            ->selectRaw('status, COUNT(*) as c')
            ->groupBy('status')
            ->pluck('c', 'status');

        $summary = [
            'total_records' => $counts->sum(),
            'pending' => $counts['pending'] ?? 0,
            'approved' => $counts['approved'] ?? 0,
            'completed' => $counts['completed'] ?? 0,
            'rejected' => $counts['rejected'] ?? 0,
            'total_shares' => $user->shareOrders()->where('type', 'sell')->sum('shares'),
            'total_received_bdt' => $user->shareOrders()->where('type', 'sell')->sum('net_bdt'),
            'total_profit_loss_bdt' => $user->shareOrders()->where('type', 'sell')->sum('profit_loss_bdt'),
        ];

        return view('user.investment.sales-history', compact(
            'user',
            'sales',
            'status',
            'summary'
        ));
    }
}