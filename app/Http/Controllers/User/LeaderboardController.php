<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class LeaderboardController extends Controller
{
    /**
     * Resell Leaderboard
     */
    public function index()
    {
        $user = auth()->user();

        $leaderboard = collect([]);

        $summary = [
            'total_participants' => 0,
            'your_rank' => null,
            'top_rank' => null,
        ];

        $period = 'all_time';

        $periods = [
            'all_time' => 'All Time',
            'monthly' => 'Monthly',
            'weekly' => 'Weekly',
        ];

        return view('user.leaderboard.index', compact(
            'user',
            'leaderboard',
            'summary',
            'period',
            'periods'
        ));
    }

    /**
     * Monthly Leaderboard
     */
    public function monthly()
    {
        $user = auth()->user();

        $leaderboard = collect([]);

        $summary = [
            'total_participants' => 0,
            'your_rank' => null,
            'monthly_referrals' => 0,
        ];

        $month = now()->format('F Y');

        return view('user.leaderboard.monthly', compact(
            'user',
            'leaderboard',
            'summary',
            'month'
        ));
    }

    /**
     * Top Performers
     */
    public function topPerformers()
    {
        $user = auth()->user();

        $performers = collect([]);

        $summary = [
            'total_performers' => 0,
            'verified_performers' => 0,
            'your_position' => null,
        ];

        $categories = [
            'Platform Participation',
            'Community Engagement',
            'Achievement Completion',
            'Account Verification',
        ];

        return view('user.leaderboard.top-performers', compact(
            'user',
            'performers',
            'summary',
            'categories'
        ));
    }
}