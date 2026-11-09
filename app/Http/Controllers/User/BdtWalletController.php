<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class BdtWalletController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $wallet = [
            'name' => 'BDT Wallet',
            'currency' => 'BDT',
            'symbol' => '৳',
            'country' => 'Bangladesh',
            'status' => 'Not Activated',
            'balance' => null,
            'description' => 'Bangladeshi Taka Wallet',
        ];

        $summary = [
            'available_balance' => null,
            'total_deposits' => null,
            'total_withdrawals' => null,
            'total_transactions' => 0,
        ];

        $transactions = [];

        return view('user.wallet.bdt', compact(
            'user',
            'wallet',
            'summary',
            'transactions'
        ));
    }
}