<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionHistoryController extends Controller
{
    /** ফিল্টারের নাম => wallet_transactions.type এর তালিকা */
    private const TYPE_GROUPS = [
        'deposit' => ['deposit', 'agent_recharge'],
        'withdrawal' => ['withdraw', 'withdraw_refund'],
        'transfer' => ['transfer_in', 'transfer_out'],
        'income' => ['daily_profit', 'referral_commission', 'reward'],
        'share' => ['share_buy', 'share_sell', 'p2p_buy', 'p2p_sell'],
    ];

    public function index(Request $request)
    {
        $user = auth()->user();

        // Currency filter
        $currency = strtoupper((string) $request->query('currency', 'all'));
        $currency = $request->query('currency') === 'all' ? 'all' : $currency;

        $allowedCurrencies = array_values(array_unique(array_merge(
            ['all', 'BDT', 'USD'],
            $user->wallets()->pluck('currency')->all()
        )));

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
            'income',
            'share',
            'other',
        ];

        if (!in_array($type, $allowedTypes, true)) {
            $type = 'all';
        }

        // কারেন্সি ফিল্টার দেওয়া বেস কুয়েরি
        $base = fn () => $user->walletTransactions()
            ->when($currency !== 'all', fn ($q) => $q->where('currency', $currency));

        $knownTypes = array_merge(
            self::TYPE_GROUPS['deposit'],
            self::TYPE_GROUPS['withdrawal'],
            self::TYPE_GROUPS['transfer'],
            self::TYPE_GROUPS['income'],
            self::TYPE_GROUPS['share'],
        );

        $transactions = $base()
            ->when(
                isset(self::TYPE_GROUPS[$type]),
                fn ($q) => $q->whereIn('type', self::TYPE_GROUPS[$type])
            )
            ->when($type === 'other', fn ($q) => $q->whereNotIn('type', $knownTypes))
            ->when($request->filled('from'), fn ($q) => $q->whereDate('created_at', '>=', $request->query('from')))
            ->when($request->filled('to'), fn ($q) => $q->whereDate('created_at', '<=', $request->query('to')))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $summary = [
            'total_transactions' => $base()->count(),
            'total_deposits' => $base()->whereIn('type', self::TYPE_GROUPS['deposit'])->count(),
            'total_withdrawals' => $base()->whereIn('type', self::TYPE_GROUPS['withdrawal'])->count(),
            'income_transactions' => $base()->whereIn('type', self::TYPE_GROUPS['income'])->count(),
            'share_transactions' => $base()->whereIn('type', self::TYPE_GROUPS['share'])->count(),
            'other_transactions' => $base()->whereNotIn('type', $knownTypes)->count(),
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