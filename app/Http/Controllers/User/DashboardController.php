<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Banner;
use App\Models\Notice;
use App\Models\PositionRank;
use App\Models\ShareOrder;
use App\Models\UserPosition;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // ---- Rank boost ----
        $boost = '0';
        $rankId = UserPosition::where('user_id', $user->id)->value('position_rank_id');
        if ($rankId) {
            $boost = (string) (PositionRank::where('id', $rankId)->where('status', true)->value('profit_boost_percent') ?? 0);
        }

        // ---- Profit summary ----
        $lastDate = $user->dailyProfitLogs()->max('profit_date');

        $profit = [
            'total'      => (string) $user->dailyProfitLogs()->sum('profit_bdt'),
            'this_month' => (string) $user->dailyProfitLogs()
                ->whereYear('profit_date', now()->year)
                ->whereMonth('profit_date', now()->month)->sum('profit_bdt'),
            'last_date'  => $lastDate,
            'last'       => $lastDate
                ? (string) $user->dailyProfitLogs()->whereDate('profit_date', $lastDate)->sum('profit_bdt')
                : '0',
            // আজ Wallet-এ যা জমা পড়েছে (গতকালের লাভ, আজ রাত ১২:০৫-এর পরে)
            'credited_today' => (string) $user->dailyProfitLogs()->whereDate('created_at', today())->sum('profit_bdt'),
            'credited_today_local' => $user->dailyProfitLogs()->whereDate('created_at', today())
                ->selectRaw('wallet_currency, SUM(wallet_amount) as total')
                ->groupBy('wallet_currency')->pluck('total', 'wallet_currency'),
            'by_currency' => $user->dailyProfitLogs()
                ->selectRaw('wallet_currency, SUM(wallet_amount) as total')
                ->groupBy('wallet_currency')->pluck('total', 'wallet_currency'),
        ];

        // ---- আজ অর্জিত (কাল রাতে জমা হবে) + portfolio ----
        $startOfToday = today()->startOfDay()->toDateTimeString();

        $accruing = '0.00';
        $expected = '0.00';
        $portfolioLabels = [];
        $portfolioValues = [];
        $totalInvested = '0.00';

        foreach ($user->shareHoldings()->where('shares', '>', 0)->with('project')->get() as $h) {
            $p = $h->project;
            if (! $p) {
                continue;
            }

            $value = bcmul((string) $p->share_price, (string) $h->shares, 2);
            $totalInvested = bcadd($totalInvested, $value, 2);
            $portfolioLabels[] = $p->name;
            $portfolioValues[] = (float) $value;

            $earning = $p->is_active && $p->status === 'active' && bccomp((string) $p->daily_profit_percent, '0', 3) > 0;

            if (! $earning) {
                continue;
            }

            // প্রতিদিন কত আসবে (বর্তমান সব শেয়ার ধরে)
            $daily = bcdiv(bcmul($value, (string) $p->daily_profit_percent, 6), '100', 2);
            if (bccomp($boost, '0', 2) > 0) {
                $daily = bcdiv(bcmul($daily, bcadd('100', $boost, 2), 6), '100', 2);
            }
            $expected = bcadd($expected, $daily, 2);

            // আজ যে শেয়ার পুরো দিন ধরা আছে (আজ কেনা শেয়ার বাদ)
            $row = ShareOrder::where('user_id', $user->id)
                ->where('project_id', $h->project_id)
                ->where('status', 'completed')
                ->selectRaw(
                    "COUNT(*) AS n,
                     COALESCE(SUM(CASE WHEN type='buy'  AND created_at < ? THEN shares ELSE 0 END),0)
                   - COALESCE(SUM(CASE WHEN type='sell' AND created_at < ? THEN shares ELSE 0 END),0) AS at_start",
                    [$startOfToday, $startOfToday]
                )->first();

            if ((int) ($row->n ?? 0) === 0) {
                $heldAll = $h->created_at && $h->created_at->lt(today()) ? (int) $h->shares : 0;
            } else {
                $heldAll = max(0, min((int) $row->at_start, (int) $h->shares));
            }

            if ($heldAll > 0) {
                $a = bcdiv(bcmul(bcmul((string) $p->share_price, (string) $heldAll, 2), (string) $p->daily_profit_percent, 6), '100', 2);
                if (bccomp($boost, '0', 2) > 0) {
                    $a = bcdiv(bcmul($a, bcadd('100', $boost, 2), 6), '100', 2);
                }
                $accruing = bcadd($accruing, $a, 2);
            }
        }

        // ---- Monthly profit chart (this year) ----
        $monthly = $user->dailyProfitLogs()
            ->whereYear('profit_date', now()->year)
            ->selectRaw('MONTH(profit_date) as m, SUM(profit_bdt) as t')
            ->groupBy('m')->pluck('t', 'm');

        $growthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $growthProfit = [];
        for ($m = 1; $m <= 12; $m++) {
            $growthProfit[] = round((float) ($monthly[$m] ?? 0), 2);
        }

        // ---- Banner / Notice / Announcement (super admin theke) ----
        $banners       = $this->live(Banner::class);
        $notices       = $this->live(Notice::class);
        $announcements = $this->live(Announcement::class);

        return view('user.dashboard', compact(
            'profit', 'expected', 'accruing', 'boost', 'totalInvested',
            'growthLabels', 'growthProfit', 'portfolioLabels', 'portfolioValues',
            'banners', 'notices', 'announcements'
        ));
    }

    private function live(string $model)
    {
        try {
            if (! class_exists($model)) {
                return collect();
            }

            $query = $model::query();
            $instance = new $model;

            if (method_exists($instance, 'scopePublished')) {
                $query->published();
            } elseif (method_exists($instance, 'scopeActive')) {
                $query->active();
            }

            return $query->latest()->limit(10)->get();
        } catch (\Throwable $e) {
            report($e);

            return collect();
        }
    }
}