<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\RewardCategory;

class AchievementRewardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $invested = (float) $user->shareHoldings()
            ->where('shares', '>', 0)
            ->get()
            ->sum(fn ($h) => (float) $h->invested_bdt);

        // Super Admin-এর সেট করা Active reward category-ই এখানে milestone হিসেবে দেখায়
        $achievements = RewardCategory::where('status', true)
            ->orderBy('min_investment')
            ->orderBy('id')
            ->get()
            ->map(function ($c) use ($invested) {
                $min = (float) $c->min_investment;

                if ($invested >= $min) {
                    $state = 'completed';
                } elseif ($invested > 0) {
                    $state = 'in_progress';
                } else {
                    $state = 'locked';
                }

                $c->state = $state;
                $c->progress = $min > 0 ? min(100, (int) floor(($invested / $min) * 100)) : 100;
                $c->remaining = max(0, $min - $invested);

                return $c;
            });

        $summary = [
            'total' => $achievements->count(),
            'completed' => $achievements->where('state', 'completed')->count(),
            'in_progress' => $achievements->where('state', 'in_progress')->count(),
            'locked' => $achievements->where('state', 'locked')->count(),
        ];

        return view('user.rewards.achievements', compact('user', 'achievements', 'summary', 'invested'));
    }
}