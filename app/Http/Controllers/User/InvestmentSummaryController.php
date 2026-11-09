<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InvestmentSummaryController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Real investment data will be connected
        // after the business model and regulatory requirements
        // have been verified.

        $summary = [
            'total_projects' => 0,
            'total_shares' => 0,
            'total_invested' => 0,
            'total_profit' => 0,
            'active_investments' => 0,
            'completed_investments' => 0,
            'pending_investments' => 0,
        ];

        $investments = collect([]);

        $projectBreakdown = collect([]);

        return view('user.investment.investment-summary', compact(
            'user',
            'summary',
            'investments',
            'projectBreakdown'
        ));
    }
}