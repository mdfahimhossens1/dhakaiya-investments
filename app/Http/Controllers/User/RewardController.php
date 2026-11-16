<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\RewardCategory;
use App\Models\RewardLog;

class RewardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $invested = (float) $user->shareHoldings()
            ->where('shares', '>', 0)
            ->get()
            ->sum(fn ($h) => (float) $h->invested_bdt);

        // সর্বশেষ ১০টি পাওয়া রিওয়ার্ড
        $rewards = RewardLog::where('user_id', $user->id)
            ->latest()
            ->limit(10)
            ->get();

        $summary = [
            'total_rewards' => RewardLog::where('user_id', $user->id)->count(),
            'by_currency' => RewardLog::where('user_id', $user->id)
                ->selectRaw('wallet_currency, SUM(wallet_amount) as total')
                ->groupBy('wallet_currency')
                ->pluck('total', 'wallet_currency'),
            'by_category' => RewardLog::where('user_id', $user->id)
                ->selectRaw('category, COUNT(*) as c')
                ->groupBy('category')
                ->pluck('c', 'category'),
        ];

        // ইউজার যেগুলো এখন আনলক করেছে
        $categories = RewardCategory::where('status', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(function ($c) use ($invested) {
                $c->is_unlocked = $invested >= (float) $c->min_investment;

                return $c;
            });

        return view('user.rewards.index', compact('user', 'rewards', 'summary', 'categories', 'invested'));
    }
}