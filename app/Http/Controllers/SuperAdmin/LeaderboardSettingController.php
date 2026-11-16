<?php

namespace App\Http\Controllers\SuperAdmin;
use App\Models\LeaderboardSetting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LeaderboardSettingController extends Controller
{
    public function index()
    {
        return view('super-admin.leaderboard-settings.index', ['setting' => LeaderboardSetting::current()]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'ranking_basis' => 'required|in:resell_profit,resell_volume,referral_count,investment_amount',
            'reset_cycle' => 'required|in:weekly,monthly,never',
            'show_top_n' => 'required|integer|min:1|max:100',
        ]);

        foreach (['resell_leaderboard_enabled','monthly_leaderboard_enabled','top_performers_enabled','show_amount_publicly'] as $flag) {
            $data[$flag] = $request->boolean($flag);
        }

        LeaderboardSetting::current()->update($data);
        return back()->with('success', 'Leaderboard settings updated.');
    }
}