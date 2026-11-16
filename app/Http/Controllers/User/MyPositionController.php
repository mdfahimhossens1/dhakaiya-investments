<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PositionHistory;
use App\Models\Project;
use App\Models\RewardLog;
use App\Models\ShareLock;
use App\Models\UserPosition;
use App\Services\PositionService;
use App\Services\ShareException;
use App\Services\ShareService;
use App\Support\Currency;
use Illuminate\Http\Request;

class MyPositionController extends Controller
{
    /**
     * Current Position
     */
    public function index(PositionService $service, ShareService $shares)
    {
        $user = auth()->user();

        $position = UserPosition::with('rank')->where('user_id', $user->id)->first();
        $rank = $position?->rank;

        $ranks = $service->ranks();
        $next = $service->nextRank($rank);
        $metrics = $service->metrics($user);

        $currency = $user->walletCurrency();
        $symbol = Currency::symbol($currency);

        // Rank-এর BDT অঙ্ক নিজের কারেন্সিতেও
        $ranks->each(function ($r) use ($shares, $currency, $position) {
            $r->min_local = $shares->fromBdt((string) $r->min_total_investment, $currency);
            $r->bonus_local = $shares->fromBdt((string) $r->bonus_amount, $currency);
            $r->is_current = $position && $position->position_rank_id === $r->id;
        });

        $requirements = $next ? $service->requirements($next, $metrics) : [];

        $overall = $requirements
            ? round(collect($requirements)->avg('percent'), 1)
            : 100;

        $eligibleAt = ($position && $rank && $position->achieved_at && ! $position->reward_paid_at)
            ? $position->achieved_at->copy()->addDays((int) $rank->lock_period_days)
            : null;

        $achievements = RewardLog::where('user_id', $user->id)->where('category', 'position')->count();

        $currentPosition = [
            'rank' => $rank?->name ?? 'Not Assigned',
            'level' => $rank?->level ?? 0,
            'status' => $rank ? 'Active' : 'Not Assigned',
            'assigned_date' => $position?->achieved_at,
            'achievements' => $achievements,
        ];

        $summary = [
            'current_level' => $currentPosition['level'],
            'total_positions' => PositionHistory::where('user_id', $user->id)->where('action', 'promoted')->count(),
            'total_achievements' => $achievements,
        ];

        $holdings = $user->shareHoldings()->where('shares', '>', 0)->with('project')->get();

        return view('user.my-position.current-position', [
            'user' => $user,
            'currentPosition' => $currentPosition,
            'summary' => $summary,
            'rank' => $rank,
            'next' => $next,
            'ranks' => $ranks,
            'metrics' => $metrics,
            'requirements' => $requirements,
            'overall' => $overall,
            'eligibleAt' => $eligibleAt,
            'rewardPaid' => (bool) $position?->reward_paid_at,
            'lockedValueLocal' => $shares->fromBdt($metrics['locked_value_bdt'], $currency),
            'currency' => $currency,
            'symbol' => $symbol,
            'holdings' => $holdings,
        ]);
    }

    /**
     * Position History
     */
    public function history()
    {
        $user = auth()->user();

        $positionHistory = PositionHistory::with(['fromRank', 'toRank'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(15);

        $position = UserPosition::with('rank')->where('user_id', $user->id)->first();

        $summary = [
            'total_positions' => PositionHistory::where('user_id', $user->id)->where('action', 'promoted')->count(),
            'current_level' => $position?->rank?->level ?? 0,
            'previous_positions' => PositionHistory::where('user_id', $user->id)->whereNotNull('from_rank_id')->count(),
        ];

        return view('user.my-position.position-history', compact(
            'user',
            'positionHistory',
            'summary'
        ));
    }

    /**
     * Locked Share Records
     */
    public function lockedShares()
    {
        $user = auth()->user();

        $lockedShareRecords = ShareLock::with('project')
            ->where('user_id', $user->id)
            ->latest('locked_at')
            ->paginate(15);

        $summary = [
            'total_locked_shares' => (int) ShareLock::where('user_id', $user->id)->where('status', 'locked')->sum('shares'),
            'total_records' => ShareLock::where('user_id', $user->id)->count(),
            'active_records' => ShareLock::where('user_id', $user->id)->where('status', 'locked')->count(),
        ];

        // Unlock ফর্মের জন্য: কোন প্রজেক্টে কয়টা Lock আছে
        $lockedHoldings = $user->shareHoldings()
            ->where('locked_shares', '>', 0)
            ->with('project')
            ->get();

        $position = UserPosition::with('rank')->where('user_id', $user->id)->first();

        return view('user.my-position.locked-shares', compact(
            'user',
            'lockedShareRecords',
            'summary',
            'lockedHoldings',
            'position'
        ));
    }

    /**
     * Achievement History
     */
    public function achievements()
    {
        $user = auth()->user();

        $achievementHistory = RewardLog::where('user_id', $user->id)
            ->where('category', 'position')
            ->latest()
            ->paginate(15);

        $position = UserPosition::with('rank')->where('user_id', $user->id)->first();
        $rank = $position?->rank;

        $pendingBonus = ($position && $rank && ! $position->reward_paid_at && $position->achieved_at)
            ? [
                'rank' => $rank->name,
                'amount_bdt' => $rank->bonus_amount,
                'eligible_at' => $position->achieved_at->copy()->addDays((int) $rank->lock_period_days),
            ]
            : null;

        $count = RewardLog::where('user_id', $user->id)->where('category', 'position')->count();

        $summary = [
            'total_achievements' => $count,
            'completed' => $count,
            'pending' => $pendingBonus ? 1 : 0,
        ];

        return view('user.my-position.achievement-history', compact(
            'user',
            'achievementHistory',
            'summary',
            'pendingBonus'
        ));
    }

    /* ------------------------------------------------------------------ */

    public function lock(Request $request, PositionService $service)
    {
        $data = $request->validate([
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'shares' => ['required', 'integer', 'min:1', 'max:1000000'],
        ]);

        try {
            $pos = $service->lock($request->user(), Project::findOrFail($data['project_id']), (int) $data['shares']);
        } catch (ShareException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        $name = $pos->rank?->name;

        return back()->with('success', $name ? "শেয়ার Lock হয়েছে। বর্তমান Position: {$name}" : 'শেয়ার Lock হয়েছে।');
    }

    public function unlock(Request $request, PositionService $service)
    {
        $data = $request->validate([
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'shares' => ['required', 'integer', 'min:1', 'max:1000000'],
        ]);

        try {
            $pos = $service->unlock($request->user(), Project::findOrFail($data['project_id']), (int) $data['shares']);
        } catch (ShareException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        $name = $pos->rank?->name ?? 'কোনো Position নেই';

        return back()->with('success', "শেয়ার Unlock হয়েছে। বর্তমান Position: {$name}");
    }
}