<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class MyPositionController extends Controller
{
    /**
     * Current Position
     */
    public function index()
    {
        $user = auth()->user();

        $currentPosition = [
            'rank' => 'Not Assigned',
            'level' => 0,
            'status' => 'Pending',
            'assigned_date' => null,
            'achievements' => 0,
        ];

        $summary = [
            'current_level' => 0,
            'total_positions' => 0,
            'total_achievements' => 0,
        ];

        return view('user.my-position.current-position', compact(
            'user',
            'currentPosition',
            'summary'
        ));
    }

    /**
     * Position History
     */
    public function history()
    {
        $user = auth()->user();

        $positionHistory = collect([]);

        $summary = [
            'total_positions' => 0,
            'current_level' => 0,
            'previous_positions' => 0,
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

        $lockedShareRecords = collect([]);

        $summary = [
            'total_locked_shares' => 0,
            'total_records' => 0,
            'active_records' => 0,
        ];

        return view('user.my-position.locked-shares', compact(
            'user',
            'lockedShareRecords',
            'summary'
        ));
    }

    /**
     * Achievement History
     */
    public function achievements()
    {
        $user = auth()->user();

        $achievementHistory = collect([]);

        $summary = [
            'total_achievements' => 0,
            'completed' => 0,
            'pending' => 0,
        ];

        return view('user.my-position.achievement-history', compact(
            'user',
            'achievementHistory',
            'summary'
        ));
    }
}
