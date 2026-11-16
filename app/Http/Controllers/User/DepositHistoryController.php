<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DepositHistoryController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $currency = strtoupper((string) $request->query('currency', 'all'));
        $currency = $request->query('currency') === 'all' ? 'all' : $currency;

        // আগের মতোই all/BDT/USD, সাথে ইউজারের নিজের Wallet কারেন্সি
        $allowedCurrencies = array_values(array_unique(array_merge(
            ['all', 'BDT', 'USD'],
            $user->wallets()->pluck('currency')->all()
        )));

        if (!in_array($currency, $allowedCurrencies, true)) {
            $currency = 'all';
        }

        $status = $request->query('status');

        $deposits = $user->depositRequests()
            ->when($currency !== 'all', fn ($q) => $q->where('currency', $currency))
            ->when(
                in_array($status, ['pending', 'approved', 'rejected'], true),
                fn ($q) => $q->where('status', $status)
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $approvedByCurrency = $user->depositRequests()
            ->where('status', 'approved')
            ->selectRaw('currency, SUM(approved_amount) as total')
            ->groupBy('currency')
            ->pluck('total', 'currency');

        $summary = [
            'total_deposits' => $user->depositRequests()->count(),
            'total_amount_bdt' => $approvedByCurrency['BDT'] ?? null,
            'total_amount_usd' => $approvedByCurrency['USD'] ?? null,
            'amounts_by_currency' => $approvedByCurrency,
            'pending_deposits' => $user->depositRequests()->where('status', 'pending')->count(),
            'approved_deposits' => $user->depositRequests()->where('status', 'approved')->count(),
            'rejected_deposits' => $user->depositRequests()->where('status', 'rejected')->count(),
        ];

        return view('user.wallet.deposit-history', compact(
            'user',
            'currency',
            'deposits',
            'summary'
        ));
    }
}