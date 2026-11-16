<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\PositionRank;
use App\Models\UserPosition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class PositionSettingController extends Controller
{
    public function index()
    {
        $ranks = PositionRank::withCount('userPositions')->orderBy('level')->paginate(15);

        $stats = [
            'total' => PositionRank::count(),
            'active' => PositionRank::where('status', true)->count(),
            'ranked_users' => UserPosition::whereNotNull('position_rank_id')->count(),
        ];

        return view('super-admin.position-settings.index', compact('ranks', 'stats'));
    }

    public function store(Request $request)
    {
        PositionRank::create($this->validated($request));
        $this->refreshAfterResponse();

        return back()->with('success', 'Rank created.');
    }

    public function show(PositionRank $positionRank)
    {
        return response()->json($positionRank);
    }

    public function update(Request $request, PositionRank $positionRank)
    {
        $positionRank->update($this->validated($request, $positionRank->id));
        $this->refreshAfterResponse();

        return back()->with('success', 'Rank updated. User positions are being recalculated.');
    }

    public function destroy(PositionRank $positionRank)
    {
        if (UserPosition::where('position_rank_id', $positionRank->id)->exists()) {
            return back()->with('error', 'কিছু ইউজার এখন এই Rank-এ আছে। আগে Rank-টা Inactive করুন, তারা নিজে থেকে নেমে যাবে, তারপর মুছুন।');
        }

        $positionRank->delete();
        $this->refreshAfterResponse();

        return back()->with('success', 'Rank deleted.');
    }

    /** হাতে সবার Position আবার হিসাব */
    public function recalculate()
    {
        Artisan::call('position:process');

        return back()->with('success', 'All positions recalculated.');
    }

    private function refreshAfterResponse(): void
    {
        app()->terminating(function () {
            Artisan::call('position:process');
        });
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        $data = $request->validate([
            'name' => 'required|string|max:80',
            'level' => 'required|integer|min:1|unique:position_ranks,level,' . $ignoreId,
            'min_total_investment' => 'required|numeric|min:0',
            'min_active_referrals' => 'required|integer|min:0',
            'min_resell_volume' => 'required|numeric|min:0',
            'bonus_amount' => 'required|numeric|min:0',
            'profit_boost_percent' => 'required|numeric|min:0|max:100',
            'lock_period_days' => 'required|integer|min:0|max:3650',
            'badge_color' => 'required|regex:/^#[0-9a-fA-F]{6}$/',
        ]);

        $data['status'] = $request->boolean('status');

        return $data;
    }
}