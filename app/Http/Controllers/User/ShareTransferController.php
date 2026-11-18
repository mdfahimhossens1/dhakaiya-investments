<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShareTransferController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $status = $request->query('status', 'all');

        if (!in_array($status, ['all', 'pending', 'approved', 'rejected', 'completed'], true)) {
            $status = 'all';
        }

        $type = $request->query('type', 'all');

        if (!in_array($type, ['all', 'buy', 'sell'], true)) {
            $type = 'all';
        }

        $transfers = $user->shareOrders()
            ->with('project:id,name,slug')
            ->when($type !== 'all', fn ($q) => $q->where('type', $type))
            ->when($status !== 'all', fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $counts = $user->shareOrders()->selectRaw('status, COUNT(*) as c')->groupBy('status')->pluck('c', 'status');

        $summary = [
            'total_records' => $counts->sum(),
            'pending' => $counts['pending'] ?? 0,
            'approved' => $counts['approved'] ?? 0,
            'completed' => $counts['completed'] ?? 0,
            'rejected' => $counts['rejected'] ?? 0,
            'bought_shares' => (int) $user->shareOrders()->where('type', 'buy')->sum('shares'),
            'resold_shares' => (int) $user->shareOrders()->where('type', 'sell')->sum('shares'),
        ];

        return view('user.investment.share-transfer', compact('user', 'transfers', 'status', 'type', 'summary'));
    }
}