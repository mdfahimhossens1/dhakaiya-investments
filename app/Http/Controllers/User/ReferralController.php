<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ReferralSetting;
use App\Services\ReferralService;
use App\Models\ReferralCommission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReferralController extends Controller
{
    /** Super Admin-এর "commission_on" অনুযায়ী লেবেল */
    private const COMMISSION_ON = [
        'share_purchase' => 'Share Purchase',
        'profit' => 'Profit',
        'both' => 'Share Purchase + Profit',
    ];

    /* ================================================================== */
    /*  Referral Link                                                     */
    /* ================================================================== */

    public function link()
    {
        $user = auth()->user();
        $setting = ReferralSetting::current();

        $enabled = (bool) $setting->is_enabled;
        $referralLink = $this->referralLink($user);
        $program = $this->program($setting);
        $totalReferrals = $user->referrals()->count();

        return view('user.referrals.link', compact(
            'user',
            'referralLink',
            'enabled',
            'program',
            'totalReferrals'
        ));
    }

    /* ================================================================== */
    /*  My Referrals                                                      */
    /* ================================================================== */

    public function index()
    {
        $user = auth()->user();
        $setting = ReferralSetting::current();

        $enabled = (bool) $setting->is_enabled;

        $referrals = $user->referrals()
            ->latest()
            ->paginate(10);

        // referral id => মোট কমিশন (BDT, cancelled বাদে)
        $earnedBySource = $this->commissions($user)
            ->selectRaw('source_user_id, SUM(commission_bdt) as total')
            ->groupBy('source_user_id')
            ->pluck('total', 'source_user_id');

        $summary = [
            'total_referrals' => $user->referrals()->count(),

            'this_month' => $user->referrals()
                ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
                ->count(),

            'total_commission_bdt' => $this->commissions($user)->sum('commission_bdt'),
        ];

        $referralLink = $this->referralLink($user);
        $program = $this->program($setting);

        return view('user.referrals.index', compact(
            'user',
            'referrals',
            'summary',
            'referralLink',
            'earnedBySource',
            'enabled',
            'program'
        ));
    }

    /* ================================================================== */
    /*  Referral Commission                                               */
    /* ================================================================== */

    public function commission(Request $request, ReferralService $service)
    {
        $user = auth()->user();
        $setting = ReferralSetting::current();

        $status = $request->query('status', 'all');

        if (! in_array($status, ['all', 'pending', 'paid'], true)) {
            $status = 'all';
        }

        $commissions = $this->commissions($user)
            ->with('sourceUser:id,name')
            ->when($status !== 'all', fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        // এক query-তে status অনুযায়ী মোট (cancelled বাদে)
        $byStatus = $this->commissions($user)
            ->selectRaw('status, SUM(commission_bdt) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $paidByCurrency = $user->referralCommissions()
            ->where('status', 'paid')
            ->selectRaw('wallet_currency, SUM(wallet_amount) as total')
            ->groupBy('wallet_currency')
            ->pluck('total', 'wallet_currency');

        $summary = [
            'total_commission' => $byStatus->reduce(fn ($c, $v) => bcadd($c, (string) $v, 2), '0'),
            'pending_commission' => (string) $byStatus->get('pending', '0'),
            'paid_commission' => (string) $byStatus->get('paid', '0'),
            'paid_by_currency' => $paidByCurrency,
        ];

        // কমিশন পেতে যোগ্য কিনা
        $myInvestment = $service->investmentOf($user);
        $required = (float) $setting->min_investment_to_qualify;

        $eligibility = [
            'enabled' => (bool) $setting->is_enabled,
            'qualified' => $service->isQualified($user, $setting),
            'required_bdt' => $setting->min_investment_to_qualify,
            'my_investment_bdt' => $myInvestment,
            'shortfall_bdt' => max(0, $required - (float) $myInvestment),
            'hold_days' => $setting->hold_days,
            'min_payout_bdt' => $setting->min_commission_withdraw,
            'max_per_referral_bdt' => $setting->max_commission_per_referral,
            'levels' => [
                1 => $setting->level1_percent,
                2 => $setting->level2_percent,
                3 => $setting->level3_percent,
            ],
            'commission_on' => $setting->commission_on,
            'commission_on_label' => self::COMMISSION_ON[$setting->commission_on] ?? $setting->commission_on,
        ];

        return view('user.referrals.commission', compact(
            'user',
            'summary',
            'commissions',
            'status',
            'eligibility'
        ));
    }

    /* ================================================================== */
    /*  Referral Statistics                                               */
    /* ================================================================== */

    public function statistics()
    {
        $user = auth()->user();
        $setting = ReferralSetting::current();

        $enabled = (bool) $setting->is_enabled;

        $summary = [
            'total_referrals' => $user->referrals()->count(),

            'this_month' => $user->referrals()
                ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
                ->count(),

            'this_year' => $user->referrals()
                ->whereBetween('created_at', [now()->startOfYear(), now()->endOfYear()])
                ->count(),

            'total_commission_bdt' => $this->commissions($user)->sum('commission_bdt'),
        ];

        // Last 6 months: মোট ২টা query
        $start = now()->subMonthsNoOverflow(5)->startOfMonth();

        $referralsByMonth = $user->referrals()
            ->where('created_at', '>=', $start)
            ->get(['created_at'])
            ->countBy(fn ($r) => $r->created_at->format('Y-m'));

        $commissionByMonth = $this->commissions($user)
            ->where('created_at', '>=', $start)
            ->get(['commission_bdt', 'created_at'])
            ->groupBy(fn ($c) => $c->created_at->format('Y-m'))
            ->map(fn ($group) => $group->reduce(
                fn ($carry, $c) => bcadd($carry, (string) $c->commission_bdt, 2),
                '0'
            ));

        $monthlyStatistics = collect(range(5, 0))->map(function ($m) use ($referralsByMonth, $commissionByMonth) {
            $date = now()->subMonthsNoOverflow($m);
            $key = $date->format('Y-m');

            return [
                'month' => $date->format('M Y'),
                'total' => (int) ($referralsByMonth[$key] ?? 0),
                'commission_bdt' => (string) ($commissionByMonth[$key] ?? '0'),
            ];
        });

        // লেভেল অনুযায়ী কমিশন (level => row)
        $byLevel = $this->commissions($user)
            ->selectRaw('level, COUNT(*) as c, SUM(commission_bdt) as total')
            ->groupBy('level')
            ->orderBy('level')
            ->get()
            ->keyBy('level');

        // লেভেল অনুযায়ী টিম সাইজ (৩ লেভেল)
        $l1Ids = $user->referrals()->pluck('id');
        $l2Ids = $l1Ids->isEmpty()
            ? collect()
            : User::whereIn('referred_by_id', $l1Ids)->pluck('id');
        $l3Count = $l2Ids->isEmpty()
            ? 0
            : User::whereIn('referred_by_id', $l2Ids)->count();

        $team = [1 => $l1Ids->count(), 2 => $l2Ids->count(), 3 => $l3Count];

        $latestReferrals = $user->referrals()
            ->latest()
            ->limit(5)
            ->get(['id', 'name', 'created_at']);

        $program = $this->program($setting);

        return view('user.referrals.statistics', compact(
            'user',
            'summary',
            'monthlyStatistics',
            'byLevel',
            'team',
            'latestReferrals',
            'enabled',
            'program'
        ));
    }

    /* ================================================================== */
    /*  Helpers                                                           */
    /* ================================================================== */

    /** cancelled বাদে এই ইউজারের কমিশন (প্রতিবার নতুন query) */
    private function commissions($user)
    {
        return $user->referralCommissions()->where('status', '!=', 'cancelled');
    }

    /** রেফারাল লিংক; referral_code না থাকলে unique code বানিয়ে নেয় */
    private function referralLink($user): ?string
    {
        if (! $user->referral_code) {
            do {
                $code = strtoupper(Str::random(8));
            } while ($user->newQuery()->where('referral_code', $code)->exists());

            $user->forceFill(['referral_code' => $code])->save();
        }

        return url('/register') . '?ref=' . $user->referral_code;
    }

    /** Super Admin-এর সেটিং থেকে ইউজারকে দেখানোর প্রোগ্রাম তথ্য */
    private function program(ReferralSetting $setting): array
    {
        $levels = collect([
            1 => $setting->level1_percent,
            2 => $setting->level2_percent,
            3 => $setting->level3_percent,
        ])->filter(fn ($p) => (float) $p > 0);   // 0% লেভেল দেখাবে না

        return [
            'levels' => $levels->all(),
            'commission_on' => $setting->commission_on,
            'commission_on_label' => self::COMMISSION_ON[$setting->commission_on] ?? $setting->commission_on,
            'min_investment_bdt' => $setting->min_investment_to_qualify,
            'max_per_referral_bdt' => $setting->max_commission_per_referral,
            'hold_days' => $setting->hold_days,
            'min_payout_bdt' => $setting->min_commission_withdraw,
        ];
    }
}