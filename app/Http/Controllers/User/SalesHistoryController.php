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

        // Real sales data will be connected after business and
        // regulatory requirements have been verified.
        $sales = collect([]);

        $summary = [
            'total_records' => 0,
            'pending' => 0,
            'approved' => 0,
            'completed' => 0,
            'rejected' => 0,
        ];

        return view('user.investment.sales-history', compact(
            'user',
            'sales',
            'status',
            'summary'
        ));
    }
}