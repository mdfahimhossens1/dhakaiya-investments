<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DailyProfitLog;
use App\Models\LeaderboardSetting;
use App\Models\ReferralCommission;
use App\Models\RewardLog;
use App\Models\ShareHolding;
use App\Models\ShareOrder;
use App\Models\User;
use App\Services\LeaderboardService;
use App\Services\ShareService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    /** Super Admin-এর "ranking_basis" অনুযায়ী লেবেল ও ধরন (টাকা না সংখ্যা) */
    private const BASIS = [
        'resell_profit' => ['label' => 'Resell Profit', 'money' => true],
        'resell_volume' => ['label' => 'Resell Volume', 'money' => true],
        'referral_count' => ['label' => 'Referral Count', 'money' => false],
        'investment_amount' => ['label' => 'Investment Amount', 'money' => true],
    ];

    private const CYCLES = [
        'weekly' => 'Weekly',
        'monthly' => 'Monthly',
        'never' => 'Never (All Time)',
    ];

    /* ================================================================== */
    /*  Main Leaderboard (Super Admin-এর ranking_basis ও reset_cycle মেনে) */
    /* ================================================================== */

    public function index(LeaderboardService $board, ShareService $shares)
    {
        $user = auth()->user();
        $setting = LeaderboardSetting::current();

        $basis = array_key_exists($setting->ranking_basis, self::BASIS) ? $setting->ranking_basis : 'resell_volume';
        $cycle = array_key_exists($setting->reset_cycle, self::CYCLES) ? $setting->reset_cycle : 'never';
        $limit = max(1, min(100, (int) $setting->show_top_n));
        $enabled = (bool) $setting->resell_leaderboard_enabled;
        $showAmounts = (bool) $setting->show_amount_publicly;

        $isMoney = self::BASIS[$basis]['money'];
        $basisLabel = self::BASIS[$basis]['label'];
        $cycleLabel = self::CYCLES[$cycle];

        [$from, $to] = match ($cycle) {
            'monthly' => [now()->startOfMonth(), now()->endOfMonth()],
            'weekly' => [now()->startOfWeek(Carbon::SATURDAY), now()->endOfWeek(Carbon::FRIDAY)],
            default => [null, null],
        };

        $currency = $user->walletCurrency();

        $leaderboard = collect();
        $summary = ['total_participants' => 0, 'your_rank' => null, 'top_value' => null];

        if ($enabled) {
            $all = $this->rankingRows($basis, $from, $to)->values()->map(
                function ($r, $i) use ($user, $board, $shares, $isMoney, $currency, $showAmounts) {
                    $isMe = (int) $r->user_id === (int) $user->id;

                    // নিজের সংখ্যা সবসময় দেখবে; অন্যদের টা শুধু show_amount_publicly চালু থাকলে
                    $canSee = $isMe || $showAmounts;

                    return (object) [
                        'rank' => $i + 1,
                        'name' => $isMe ? $r->name . ' (You)' : $board->mask($r->name),
                        'is_me' => $isMe,
                        'can_see_amount' => $canSee,
                        'value' => $canSee ? (float) $r->value : null,
                        'value_local' => ($isMoney && $canSee) ? $shares->fromBdt((string) $r->value, $currency) : null,
                        'shares' => $canSee ? (int) $r->shares : null,
                        'trades' => (int) $r->trades,
                        'raw_value' => (float) $r->value, // শুধু internal (top_value-র জন্য), blade-এ ব্যবহার করবেন না
                    ];
                }
            );

            $leaderboard = $all->take($limit);

            $summary = [
                'total_participants' => $all->count(),
                'your_rank' => $all->firstWhere('is_me', true)?->rank,   // শীর্ষ ১০০-এর বাইরে হলে null
                'top_value' => $showAmounts ? $all->first()?->raw_value : null,
            ];
        }

        $prizes = config('rewards.resell.top', []);

        return view('user.leaderboard.index', compact(
            'user', 'leaderboard', 'summary', 'prizes', 'currency',
            'enabled', 'showAmounts', 'isMoney', 'basisLabel', 'cycleLabel', 'limit', 'from', 'to'
        ));
    }

    /* ================================================================== */
    /*  Monthly Leaderboard (এই মাসে সবচেয়ে বেশি রেফার)                    */
    /* ================================================================== */

    public function monthly(LeaderboardService $board)
    {
        $user = auth()->user();
        $setting = LeaderboardSetting::current();

        $enabled = (bool) $setting->monthly_leaderboard_enabled;
        $limit = max(1, min(100, (int) $setting->show_top_n));
        $month = now()->format('F Y');

        $leaderboard = collect();
        $summary = ['total_participants' => 0, 'your_rank' => null, 'monthly_referrals' => 0];

        if ($enabled) {
            $rows = User::query()
                ->join('users as r', 'r.referred_by_id', '=', 'users.id')
                ->where('users.status', 'active')
                ->where('users.role', 'user')
                ->whereYear('r.created_at', now()->year)
                ->whereMonth('r.created_at', now()->month)
                ->groupBy('users.id', 'users.name')
                ->selectRaw('users.id, users.name, COUNT(r.id) as referrals')
                ->orderByDesc('referrals')
                ->orderBy('users.id')
                ->limit(100)
                ->get();

            $all = $rows->values()->map(function ($r, $i) use ($user, $board) {
                $isMe = (int) $r->id === (int) $user->id;

                return (object) [
                    'rank' => $i + 1,
                    'name' => $isMe ? $r->name . ' (You)' : $board->mask($r->name),
                    'is_me' => $isMe,
                    'referrals' => (int) $r->referrals,
                ];
            });

            $leaderboard = $all->take($limit);

            $summary = [
                'total_participants' => $all->count(),
                'your_rank' => $all->firstWhere('is_me', true)?->rank,
                'monthly_referrals' => $user->referrals()
                    ->whereYear('created_at', now()->year)
                    ->whereMonth('created_at', now()->month)
                    ->count(),
            ];
        }

        return view('user.leaderboard.monthly', compact(
            'user', 'leaderboard', 'summary', 'month', 'enabled', 'limit'
        ));
    }

    /* ================================================================== */
    /*  Top Performers (মোট আয়: লাভ + কমিশন + reward, BDT-তে)               */
    /* ================================================================== */

    public function topPerformers(LeaderboardService $board, ShareService $shares)
    {
        $user = auth()->user();
        $setting = LeaderboardSetting::current();

        $enabled = (bool) $setting->top_performers_enabled;
        $showAmounts = (bool) $setting->show_amount_publicly;
        $limit = max(1, min(100, (int) $setting->show_top_n));
        $currency = $user->walletCurrency();

        $performers = collect();
        $summary = ['total_performers' => 0, 'your_position' => null];

        if ($enabled) {
            $profit = DailyProfitLog::selectRaw('user_id, SUM(profit_bdt) as t')
                ->groupBy('user_id')->pluck('t', 'user_id');

            $commission = ReferralCommission::where('status', 'paid')
                ->selectRaw('user_id, SUM(commission_bdt) as t')
                ->groupBy('user_id')->pluck('t', 'user_id');

            $reward = RewardLog::selectRaw('user_id, SUM(amount_bdt) as t')
                ->groupBy('user_id')->pluck('t', 'user_id');

            $totals = [];

            foreach ([$profit, $commission, $reward] as $set) {
                foreach ($set as $uid => $amount) {
                    $totals[$uid] = bcadd($totals[$uid] ?? '0', (string) $amount, 2);
                }
            }

            uasort($totals, fn ($a, $b) => bccomp($b, $a, 2));

            // inactive/admin ইউজার বাদ পড়তে পারে, তাই একটু বেশি নিয়ে পরে ১০০-তে কাটছি
            $top = array_slice($totals, 0, 300, true);

            $names = User::whereIn('id', array_keys($top))
                ->where('status', 'active')
                ->where('role', 'user')
                ->pluck('name', 'id');

            $rank = 0;
            $all = collect();

            foreach ($top as $uid => $amount) {
                $uid = (int) $uid;

                if (! isset($names[$uid])) {
                    continue;
                }

                if ($rank >= 100) {
                    break;
                }

                $rank++;

                $isMe = $uid === (int) $user->id;
                $canSee = $isMe || $showAmounts;

                $all->push((object) [
                    'rank' => $rank,
                    'name' => $isMe ? $names[$uid] . ' (You)' : $board->mask($names[$uid]),
                    'is_me' => $isMe,
                    'can_see_amount' => $canSee,
                    'income_bdt' => $canSee ? $amount : null,
                    'income_local' => $canSee ? $shares->fromBdt($amount, $currency) : null,
                    'profit_bdt' => $canSee ? (string) ($profit[$uid] ?? 0) : null,
                    'commission_bdt' => $canSee ? (string) ($commission[$uid] ?? 0) : null,
                    'reward_bdt' => $canSee ? (string) ($reward[$uid] ?? 0) : null,
                ]);
            }

            $performers = $all->take($limit);

            $summary = [
                'total_performers' => $all->count(),
                'your_position' => $all->firstWhere('is_me', true)?->rank,
            ];
        }

        return view('user.leaderboard.top-performers', compact(
            'user', 'performers', 'summary', 'currency', 'enabled', 'showAmounts', 'limit'
        ));
    }

    /* ================================================================== */
    /*  Helpers                                                           */
    /* ================================================================== */

    /**
     * ranking_basis অনুযায়ী শীর্ষ ১০০ জন।
     * প্রতিটি সারিতে একই কলাম: user_id, name, value, shares, trades
     */
    private function rankingRows(string $basis, $from, $to)
    {
        if (in_array($basis, ['resell_profit', 'resell_volume'], true)) {
            $o = (new ShareOrder)->getTable();
            $expr = $basis === 'resell_profit' ? "SUM($o.profit_loss_bdt)" : "SUM($o.net_bdt)";

            return DB::table($o)
                ->join('users', 'users.id', '=', "$o.user_id")
                ->where("$o.type", 'sell')
                ->where("$o.status", '!=', 'rejected')
                ->where('users.status', 'active')
                ->where('users.role', 'user')
                ->when($from, fn ($q) => $q->where("$o.created_at", '>=', $from))
                ->when($to, fn ($q) => $q->where("$o.created_at", '<=', $to))
                ->groupBy('users.id', 'users.name')
                ->selectRaw("users.id as user_id, users.name, $expr as value, SUM($o.shares) as shares, COUNT($o.id) as trades")
                ->orderByDesc('value')
                ->orderBy('users.id')
                ->limit(100)
                ->get();
        }

        if ($basis === 'referral_count') {
            return DB::table('users')
                ->join('users as r', 'r.referred_by_id', '=', 'users.id')
                ->where('users.status', 'active')
                ->where('users.role', 'user')
                ->when($from, fn ($q) => $q->where('r.created_at', '>=', $from))
                ->when($to, fn ($q) => $q->where('r.created_at', '<=', $to))
                ->groupBy('users.id', 'users.name')
                ->selectRaw('users.id as user_id, users.name, COUNT(r.id) as value, 0 as shares, 0 as trades')
                ->orderByDesc('value')
                ->orderBy('users.id')
                ->limit(100)
                ->get();
        }

        // investment_amount: এখন যে শেয়ার ধরে আছে তার মোট বিনিয়োগ
        $h = (new ShareHolding)->getTable();

        return DB::table($h)
            ->join('users', 'users.id', '=', "$h.user_id")
            ->where("$h.shares", '>', 0)
            ->where('users.status', 'active')
            ->where('users.role', 'user')
            ->groupBy('users.id', 'users.name')
            ->selectRaw("users.id as user_id, users.name, SUM($h.invested_bdt) as value, SUM($h.shares) as shares, 0 as trades")
            ->orderByDesc('value')
            ->orderBy('users.id')
            ->limit(100)
            ->get();
    }
}