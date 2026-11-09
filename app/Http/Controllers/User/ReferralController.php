<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class ReferralController extends Controller
{
    /**
     * Referral Link
     */
    public function link()
    {
        $user = auth()->user();

        $referralLink = $user->referral_code
            ? url('/register') . '?ref=' . $user->referral_code
            : null;

        return view('user.referrals.link', compact(
            'user',
            'referralLink'
        ));
    }

    /**
     * My Referrals
     */
    public function index()
    {
        $user = auth()->user();

        $referrals = $user->referrals()
            ->latest()
            ->paginate(10);

        $summary = [
            'total_referrals' => $user->referrals()->count(),

            'this_month' => $user->referrals()
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        // Generate referral link
        $referralLink = $user->referral_code
            ? url('/register') . '?ref=' . $user->referral_code
            : null;

        return view('user.referrals.index', compact(
            'user',
            'referrals',
            'summary',
            'referralLink'
        ));
    }

    /**
     * Referral Commission
     */
    public function commission()
    {
        $user = auth()->user();

        $summary = [
            'total_commission' => 0,
            'pending_commission' => 0,
            'paid_commission' => 0,
        ];

        $commissions = collect([]);

        return view('user.referrals.commission', compact(
            'user',
            'summary',
            'commissions'
        ));
    }

    /**
     * Referral Statistics
     */
    public function statistics()
    {
        $user = auth()->user();

        $totalReferrals = $user->referrals()->count();

        $thisMonth = $user->referrals()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $thisYear = $user->referrals()
            ->whereYear('created_at', now()->year)
            ->count();

        $summary = [
            'total_referrals' => $totalReferrals,
            'this_month' => $thisMonth,
            'this_year' => $thisYear,
        ];

        // Last 6 months referral statistics
        $monthlyStatistics = collect(range(5, 0))->map(function ($month) use ($user) {
            $date = now()->subMonths($month);

            return [
                'month' => $date->format('M Y'),

                'total' => $user->referrals()
                    ->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->count(),
            ];
        });

        return view('user.referrals.statistics', compact(
            'user',
            'summary',
            'monthlyStatistics'
        ));
    }
}
