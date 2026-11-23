<?php

namespace App\Http\Controllers\Agent;

class DashboardController extends BaseAgentController
{
    public function index()
    {
        $agent = $this->agent()->load('districts.country');

        $deposits = $agent->deposits()
            ->selectRaw('status, COUNT(*) as c, COALESCE(SUM(amount), 0) as total')
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $recharges = $agent->recharges()->where('status', 'completed');

        $money = [
            'balance'         => (string) $agent->recharge_balance,
            'total_deposited' => (string) ($deposits->get('approved')?->total ?? 0),
            'pending_deposit' => (string) ($deposits->get('pending')?->total ?? 0),
            'pending_count'   => (int) ($deposits->get('pending')?->c ?? 0),
            'total_recharged' => (string) (clone $recharges)->sum('amount'),
            'recharge_count'  => (clone $recharges)->count(),
            'today_recharged' => (string) (clone $recharges)->where('created_at', '>=', now()->startOfDay())->sum('amount'),
        ];

        $recentRecharges = $agent->recharges()->with('user:id,name')->latest()->limit(6)->get();
        $recentDeposits = $agent->deposits()->latest()->limit(5)->get();

        return view('agent.dashboard', compact('agent', 'money', 'recentRecharges', 'recentDeposits'));
    }
}