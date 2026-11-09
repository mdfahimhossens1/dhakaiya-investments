<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountSummaryController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Total referrals
        $totalReferrals = $user->referrals()->count();

        // Recent referrals
        $recentReferrals = $user->referrals()
            ->latest()
            ->take(5)
            ->get();

        // Account information
        $accountInfo = [
            'name' => $user->name,
            'phone' => $user->phone,
            'email' => $user->email,
            'country' => $user->country,
            'role' => $user->role,
            'referral_code' => $user->referral_code,
            'registration_date' => $user->created_at,
        ];

        return view('user.account-summary', compact(
            'user',
            'totalReferrals',
            'recentReferrals',
            'accountInfo'
        ));
    }
}