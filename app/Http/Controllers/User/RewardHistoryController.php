<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\RewardLog;
use Illuminate\Http\Request;

class RewardHistoryController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $category = $request->query('category', 'all');

        if (!in_array($category, ['all', 'position', 'resell', 'referral'], true)) {
            $category = 'all';
        }

        $rewards = RewardLog::where('user_id', $user->id)
            ->when($category !== 'all', fn ($q) => $q->where('category', $category))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $summary = [
            'total' => RewardLog::where('user_id', $user->id)->count(),
            // কারেন্সি মিশিয়ে যোগ করা হয়নি
            'by_currency' => RewardLog::where('user_id', $user->id)
                ->selectRaw('wallet_currency, SUM(wallet_amount) as total')
                ->groupBy('wallet_currency')
                ->pluck('total', 'wallet_currency'),
            'by_category' => RewardLog::where('user_id', $user->id)
                ->selectRaw('category, COUNT(*) as c')
                ->groupBy('category')
                ->pluck('c', 'category'),
        ];

        return view('user.rewards.history', compact('user', 'rewards', 'summary', 'category'));
    }
}