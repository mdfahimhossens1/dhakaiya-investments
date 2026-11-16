<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PurchaseHistoryController extends Controller
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

        $purchases = $user->shareOrders()
            ->where('type', 'buy')
            ->with(['project.company'])
            ->when($status !== 'all', fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $counts = $user->shareOrders()
            ->where('type', 'buy')
            ->selectRaw('status, COUNT(*) as c')
            ->groupBy('status')
            ->pluck('c', 'status');

        $summary = [
            'total_records' => $counts->sum(),
            'pending' => $counts['pending'] ?? 0,
            'approved' => $counts['approved'] ?? 0,
            'completed' => $counts['completed'] ?? 0,
            'rejected' => $counts['rejected'] ?? 0,
            'total_shares' => $user->shareOrders()->where('type', 'buy')->sum('shares'),
            'total_spent_bdt' => $user->shareOrders()->where('type', 'buy')->sum('net_bdt'),
        ];

        return view('user.investment.purchase-history', compact(
            'user',
            'purchases',
            'status',
            'summary'
        ));
    }
}