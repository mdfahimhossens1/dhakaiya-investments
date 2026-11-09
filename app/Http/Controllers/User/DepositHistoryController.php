<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DepositHistoryController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $currency = $request->query('currency', 'all');

        $allowedCurrencies = ['all', 'BDT', 'USD'];

        if (!in_array($currency, $allowedCurrencies, true)) {
            $currency = 'all';
        }

        // Real deposit records are not activated yet.
        $deposits = collect([]);

        $summary = [
            'total_deposits' => 0,
            'total_amount_bdt' => null,
            'total_amount_usd' => null,
            'pending_deposits' => 0,
        ];

        return view('user.wallet.deposit-history', compact(
            'user',
            'currency',
            'deposits',
            'summary'
        ));
    }
}