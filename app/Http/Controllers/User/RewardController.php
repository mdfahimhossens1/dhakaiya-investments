<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RewardController extends Controller
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

        // Real reward records will be connected later.
        $rewards = collect([]);

        $summary = [
            'total_rewards' => 0,
            'pending' => 0,
            'approved' => 0,
            'rejected' => 0,
        ];

        return view('user.rewards.index', compact(
            'user',
            'rewards',
            'summary',
            'status'
        ));
    }
}