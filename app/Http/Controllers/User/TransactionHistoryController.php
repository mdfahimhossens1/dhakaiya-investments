<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionHistoryController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Currency filter
        $currency = $request->query('currency', 'all');

        $allowedCurrencies = ['all', 'BDT', 'USD'];

        if (!in_array($currency, $allowedCurrencies, true)) {
            $currency = 'all';
        }

        // Transaction type filter
        $type = $request->query('type', 'all');

        $allowedTypes = [
            'all',
            'deposit',
            'withdrawal',
            'transfer',
            'other',
        ];

        if (!in_array($type, $allowedTypes, true)) {
            $type = 'all';
        }

        // Real transaction system is not activated yet.
        $transactions = collect([]);

        $summary = [
            'total_transactions' => 0,
            'total_deposits' => 0,
            'total_withdrawals' => 0,
            'other_transactions' => 0,
        ];

        return view('user.wallet.transaction-history', compact(
            'user',
            'currency',
            'type',
            'transactions',
            'summary'
        ));
    }
}