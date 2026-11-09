<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfitLossHistoryController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $type = $request->query('type', 'all');

        $allowedTypes = [
            'all',
            'profit',
            'loss',
        ];

        if (!in_array($type, $allowedTypes, true)) {
            $type = 'all';
        }

        // Financial records remain disabled until the business
        // model and applicable regulatory requirements are verified.
        $records = collect([]);

        $summary = [
            'total_profit' => 0,
            'total_loss' => 0,
            'net_result' => 0,
            'total_records' => 0,
            'profit_records' => 0,
            'loss_records' => 0,
        ];

        return view('user.investment.profit-loss-history', compact(
            'user',
            'records',
            'summary',
            'type'
        ));
    }
}