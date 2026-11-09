<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WithdrawalHistoryController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $currency = $request->query('currency', 'all');

        $allowedCurrencies = ['all', 'BDT', 'USD'];

        if (!in_array($currency, $allowedCurrencies, true)) {
            $currency = 'all';
        }

        // Real withdrawal records are not activated yet.
        $withdrawals = collect([]);

        $summary = [
            'total_withdrawals' => 0,
            'total_amount_bdt' => null,
            'total_amount_usd' => null,
            'pending_withdrawals' => 0,
            'approved_withdrawals' => 0,
            'rejected_withdrawals' => 0,
        ];

        return view('user.wallet.withdrawal-history', compact(
            'user',
            'currency',
            'withdrawals',
            'summary'
        ));
    }
}