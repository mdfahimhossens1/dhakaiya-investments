<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class UsdWalletController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $wallet = [
            'name' => 'USD Wallet',
            'currency' => 'USD',
            'symbol' => '$',
            'country' => 'International',
            'status' => 'Not Activated',
            'balance' => null,
            'description' => 'United States Dollar Wallet',
        ];

        $summary = [
            'available_balance' => null,
            'total_deposits' => null,
            'total_withdrawals' => null,
            'total_transactions' => 0,
        ];

        $transactions = [];

        return view('user.wallet.usd', compact(
            'user',
            'wallet',
            'summary',
            'transactions'
        ));
    }
}