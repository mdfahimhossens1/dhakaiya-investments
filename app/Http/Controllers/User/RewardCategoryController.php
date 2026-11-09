<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class RewardCategoryController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Display-only categories until the official reward policy is configured.
        $categories = collect([]);

        return view('user.rewards.categories', compact(
            'user',
            'categories'
        ));
    }
}