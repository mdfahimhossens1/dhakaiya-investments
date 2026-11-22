<?php

namespace App\Http\Controllers\Agent;

use Illuminate\Http\Request;

class TransactionController extends BaseAgentController
{
    private const TYPES = ['deposit', 'recharge', 'commission', 'adjustment'];

    public function index(Request $request)
    {
        $agent = $this->agent();

        $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date'],
        ]);

        $type = $request->query('type', 'all');

        if (! in_array($type, array_merge(['all'], self::TYPES), true)) {
            $type = 'all';
        }

        $transactions = $agent->transactions()
            ->when($type !== 'all', fn ($q) => $q->where('type', $type))
            ->when($request->filled('from'), fn ($q) => $q->whereDate('created_at', '>=', $request->query('from')))
            ->when($request->filled('to'), fn ($q) => $q->whereDate('created_at', '<=', $request->query('to')))
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view('agent.transactions.index', [
            'agent' => $agent,
            'transactions' => $transactions,
            'type' => $type,
            'types' => self::TYPES,
        ]);
    }
}