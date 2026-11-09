<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RewardHistoryController extends Controller
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
        ];

        if (!in_array($status, $allowedStatuses, true)) {
            $status = 'all';
        }

        // Actual reward records will be connected after policy approval.
        $rewards = collect([]);

        $summary = [
            'total' => 0,
            'pending' => 0,
            'approved' => 0,
            'rejected' => 0,
        ];

        return view('user.rewards.history', compact(
            'user',
            'rewards',
            'summary',
            'status'
        ));
    }
}