<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MySharesController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $status = $request->query('status', 'all');

        $allowedStatuses = [
            'all',
            'active',
            'pending',
            'completed',
        ];

        if (!in_array($status, $allowedStatuses, true)) {
            $status = 'all';
        }

        // Real share records are not activated yet.
        $shares = collect([]);

        $summary = [
            'total_records' => 0,
            'active_records' => 0,
            'pending_records' => 0,
            'completed_records' => 0,
        ];

        return view('user.investment.my-shares', compact(
            'user',
            'shares',
            'status',
            'summary'
        ));
    }
}