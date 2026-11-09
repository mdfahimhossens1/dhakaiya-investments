<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class PaymentInformationController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Payment information is currently display-only.
        // No bank or mobile financial account details are collected.

        $paymentMethods = [
            [
                'name' => 'Bank Transfer',
                'description' => 'Bank payment information',
                'icon' => '🏦',
                'status' => 'Not Configured',
            ],
            [
                'name' => 'bKash',
                'description' => 'Mobile financial service',
                'icon' => '📱',
                'status' => 'Not Configured',
            ],
            [
                'name' => 'Nagad',
                'description' => 'Mobile financial service',
                'icon' => '📱',
                'status' => 'Not Configured',
            ],
            [
                'name' => 'Rocket',
                'description' => 'Mobile financial service',
                'icon' => '📱',
                'status' => 'Not Configured',
            ],
        ];

        return view('user.profile.payment-information', compact(
            'user',
            'paymentMethods'
        ));
    }
}