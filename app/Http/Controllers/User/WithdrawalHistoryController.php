<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WithdrawalHistoryController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $currency = strtoupper((string) $request->query('currency', 'all'));
        $currency = $request->query('currency') === 'all' ? 'all' : $currency;

        $allowedCurrencies = array_values(array_unique(array_merge(
            ['all', 'BDT', 'USD'],
            $user->wallets()->pluck('currency')->all()
        )));

        if (!in_array($currency, $allowedCurrencies, true)) {
            $currency = 'all';
        }

        $status = $request->query('status');

        $withdrawals = $user->withdrawRequests()
            ->when($currency !== 'all', fn ($q) => $q->where('currency', $currency))
            ->when(
                in_array($status, ['pending', 'approved', 'rejected'], true),
                fn ($q) => $q->where('status', $status)
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $paidByCurrency = $user->withdrawRequests()
            ->where('status', 'approved')
            ->selectRaw('currency, SUM(net_amount) as total')
            ->groupBy('currency')
            ->pluck('total', 'currency');

        $summary = [
            'total_withdrawals' => $user->withdrawRequests()->count(),
            'total_amount_bdt' => $paidByCurrency['BDT'] ?? null,
            'total_amount_usd' => $paidByCurrency['USD'] ?? null,
            'amounts_by_currency' => $paidByCurrency,
            'pending_withdrawals' => $user->withdrawRequests()->where('status', 'pending')->count(),
            'approved_withdrawals' => $user->withdrawRequests()->where('status', 'approved')->count(),
            'rejected_withdrawals' => $user->withdrawRequests()->where('status', 'rejected')->count(),
        ];

        return view('user.wallet.withdrawal-history', compact(
            'user',
            'currency',
            'withdrawals',
            'summary'
        ));
    }
}