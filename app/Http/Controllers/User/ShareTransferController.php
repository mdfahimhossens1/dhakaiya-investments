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

        // Transfer and resale records will be connected
        // only after the business and regulatory model is verified.
        $transfers = collect([]);

        $summary = [
            'total_records' => 0,
            'pending' => 0,
            'approved' => 0,
            'completed' => 0,
            'rejected' => 0,
        ];

        return view('user.investment.share-transfer', compact(
            'user',
            'transfers',
            'status',
            'summary'
        ));
    }
}