<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ReferralCommission;
use App\Models\ReferralSetting;
use App\Models\User;
use Illuminate\Http\Request;

class ReferralSettingController extends Controller
{
    public function index()
    {
        // এক query-তে status অনুযায়ী count ও মোট
        $sums = ReferralCommission::selectRaw('status, COUNT(*) as c, SUM(commission_bdt) as total')
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $stats = [
            'pending_bdt' => $sums->get('pending')?->total ?? 0,
            'paid_bdt' => $sums->get('paid')?->total ?? 0,
            'cancelled_bdt' => $sums->get('cancelled')?->total ?? 0,
            'pending_count' => (int) ($sums->get('pending')?->c ?? 0),
            'paid_count' => (int) ($sums->get('paid')?->c ?? 0),
            'cancelled_count' => (int) ($sums->get('cancelled')?->c ?? 0),

            'total_referrals' => User::whereNotNull('referred_by_id')->count(),
            'this_month_referrals' => User::whereNotNull('referred_by_id')
                ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
                ->count(),
        ];

        $topReferrers = User::withCount('referrals')
            ->having('referrals_count', '>', 0)
            ->orderByDesc('referrals_count')
            ->orderBy('id')
            ->limit(5)
            ->get(['id', 'name']);

        return view('super-admin.referral-settings.index', [
            'setting' => ReferralSetting::current(),
            'stats' => $stats,
            'topReferrers' => $topReferrers,
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'commission_on' => ['required', 'in:share_purchase,profit,both'],
            'level1_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'level2_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'level3_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'min_investment_to_qualify' => ['required', 'numeric', 'min:0'],
            'max_commission_per_referral' => ['nullable', 'numeric', 'min:0'],
            'hold_days' => ['required', 'integer', 'min:0', 'max:365'],
            'min_commission_withdraw' => ['required', 'numeric', 'min:0'],
        ], [
            'commission_on.in' => 'Commission On অবশ্যই Share Purchase, Profit অথবা Both হতে হবে।',
            'hold_days.max' => 'Hold days সর্বোচ্চ ৩৬৫ দিন হতে পারবে।',
        ]);

        // তিন লেভেলের মোট শতাংশ ১০০ এর বেশি হতে পারবে না
        $total = bcadd(
            bcadd((string) $data['level1_percent'], (string) $data['level2_percent'], 2),
            (string) $data['level3_percent'],
            2
        );

        if (bccomp($total, '100', 2) === 1) {
            return back()->withInput()->withErrors([
                'level1_percent' => 'তিন লেভেলের মোট শতাংশ ১০০ এর বেশি হতে পারবে না।',
            ]);
        }

        // ফাঁকা থাকলে null = কোনো সীমা নেই
        $data['max_commission_per_referral'] = $data['max_commission_per_referral'] ?? null;

        $data['is_enabled'] = $request->boolean('is_enabled');

        ReferralSetting::current()->update($data);

        return back()->with('success', 'Referral settings updated.');
    }
}