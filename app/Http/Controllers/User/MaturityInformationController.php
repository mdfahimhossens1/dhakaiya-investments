<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MaturityInformationController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $status = $request->query('status', 'all');

        $allowedStatuses = [
            'all',
            'upcoming',
            'matured',
            'completed',
            'pending',
        ];

        if (!in_array($status, $allowedStatuses, true)) {
            $status = 'all';
        }

        // Real maturity records will be connected later.
        $maturities = collect([]);

        $summary = [
            'total_records' => 0,
            'upcoming' => 0,
            'matured' => 0,
            'completed' => 0,
            'pending' => 0,
        ];

        return view('user.investment.maturity-information', compact(
            'user',
            'maturities',
            'summary',
            'status'
        ));
    }
}