<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\RewardCategory;

class RewardCategoryController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // ইউজারের বর্তমান মোট বিনিয়োগ (BDT), যে শেয়ার এখনো ধরে আছে সেগুলো থেকে
        $invested = (float) $user->shareHoldings()
            ->where('shares', '>', 0)
            ->get()
            ->sum(fn ($h) => (float) $h->invested_bdt);

        // Super Admin যে Active ক্যাটাগরিগুলো যোগ করেছে সেগুলোই দেখাবে
        $categories = RewardCategory::where('status', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(function ($c) use ($invested) {
                $min = (float) $c->min_investment;

                $c->is_unlocked = $invested >= $min;
                $c->progress = $min > 0 ? min(100, (int) floor(($invested / $min) * 100)) : 100;
                $c->remaining = max(0, $min - $invested);

                return $c;
            });

        return view('user.rewards.categories', compact('user', 'categories', 'invested'));
    }
}