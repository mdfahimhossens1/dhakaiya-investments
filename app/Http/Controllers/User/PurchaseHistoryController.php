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

        $purchases = collect([]);

        $summary = [
            'total_records' => 0,
            'pending' => 0,
            'approved' => 0,
            'completed' => 0,
            'rejected' => 0,
        ];

        return view('user.investment.purchase-history', compact(
            'user',
            'purchases',
            'status',
            'summary'
        ));
    }
}