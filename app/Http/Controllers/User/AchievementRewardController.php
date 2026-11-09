<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class AchievementRewardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $achievements = collect([]);

        $summary = [
            'total' => 0,
            'completed' => 0,
            'in_progress' => 0,
            'locked' => 0,
        ];

        $categories = [
            [
                'name' => 'Profile Completion',
                'description' => 'Complete your account profile.',
                'icon' => 'fa-user-check',
            ],
            [
                'name' => 'Membership Milestones',
                'description' => 'Membership-related milestones.',
                'icon' => 'fa-award',
            ],
            [
                'name' => 'Platform Participation',
                'description' => 'Participation in platform activities.',
                'icon' => 'fa-users',
            ],
            [
                'name' => 'Learning & Engagement',
                'description' => 'Learning and engagement milestones.',
                'icon' => 'fa-graduation-cap',
            ],
        ];

        return view('user.rewards.achievements', compact(
            'user',
            'achievements',
            'summary',
            'categories'
        ));
    }
}