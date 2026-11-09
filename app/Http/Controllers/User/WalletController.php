<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class WalletController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Wallet functionality is not activated yet.
        // No real balance or transaction data is displayed.

        $wallets = [
            [
                'name' => 'BDT Wallet',
                'currency' => 'BDT',
                'symbol' => '৳',
                'description' => 'Bangladeshi Taka Wallet',
                'status' => 'Not Activated',
                'icon' => '🇧🇩',
            ],
            [
                'name' => 'USD Wallet',
                'currency' => 'USD',
                'symbol' => '$',
                'description' => 'United States Dollar Wallet',
                'status' => 'Not Activated',
                'icon' => '🇺🇸',
            ],
        ];

        $summary = [
            'total_wallets' => count($wallets),
            'active_wallets' => 0,
            'total_deposits' => null,
            'total_withdrawals' => null,
            'total_transactions' => null,
        ];

        return view('user.wallet.index', compact(
            'user',
            'wallets',
            'summary'
        ));
    }
    

    /**
 * Withdraw
 */
public function withdraw()
{
    $user = auth()->user();

    $withdrawalSummary = [
        'available_balance' => 0,
        'minimum_withdrawal' => 0,
        'pending_withdrawals' => 0,
        'completed_withdrawals' => 0,
    ];

    $withdrawalMethods = collect([]);

    return view('user.withdraw.index', compact(
        'user',
        'withdrawalSummary',
        'withdrawalMethods'
    ));
}

/**
 * Withdrawal Methods
 */
public function withdrawalMethods()
{
    $user = auth()->user();

    $withdrawalMethods = [
        [
            'name' => 'Bank Transfer',
            'description' => 'Bank account withdrawal method',
            'icon' => 'fa-solid fa-building-columns',
            'status' => 'Not Activated',
        ],
        [
            'name' => 'Digital Payment',
            'description' => 'Digital payment method',
            'icon' => 'fa-solid fa-mobile-screen-button',
            'status' => 'Not Activated',
        ],
        [
            'name' => 'E-Wallet',
            'description' => 'Electronic wallet method',
            'icon' => 'fa-solid fa-wallet',
            'status' => 'Not Activated',
        ],
    ];

    return view('user.withdrawal-methods.index', compact(
        'user',
        'withdrawalMethods'
    ));
}
}