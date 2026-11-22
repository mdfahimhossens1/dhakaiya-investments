<?php

namespace App\Http\Controllers\Agent;

use App\Models\AgentMessage;

class DashboardController extends BaseAgentController
{
    public function index()
    {
        $agent = $this->agent()->load('districts.country');

        // Deposit: status অনুযায়ী count ও মোট (এক query)
        $deposits = $agent->deposits()
            ->selectRaw('status, COUNT(*) as c, COALESCE(SUM(amount), 0) as total')
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        // Recharge: সম্পন্ন রিচার্জের মোট, সংখ্যা ও আজকের মোট
        $recharges = $agent->recharges()->where('status', 'completed');

        $money = [
            'balance'          => (string) $agent->recharge_balance,
            'total_deposited'  => (string) ($deposits->get('approved')?->total ?? 0),
            'pending_deposit'  => (string) ($deposits->get('pending')?->total ?? 0),
            'pending_count'    => (int) ($deposits->get('pending')?->c ?? 0),
            'total_recharged'  => (string) (clone $recharges)->sum('amount'),
            'recharge_count'   => (clone $recharges)->count(),
            'today_recharged'  => (string) (clone $recharges)->where('created_at', '>=', now()->startOfDay())->sum('amount'),
            'commission'       => (string) $agent->transactions()->where('type', 'commission')->sum('amount'),
        ];

        $recentRecharges = $agent->recharges()
            ->with('user:id,name')
            ->latest()
            ->limit(6)
            ->get();

        $recentDeposits = $agent->deposits()->latest()->limit(5)->get();

        // Research (আগের ফিচার, রাখা হয়েছে)
        $counts = $agent->requests()
            ->selectRaw('status, COUNT(*) as c')
            ->groupBy('status')
            ->pluck('c', 'status');

        $unread = AgentMessage::where('agent_id', $agent->id)
            ->where('sender_id', '!=', $agent->user_id)
            ->whereNull('read_at')
            ->count();

        return view('agent.dashboard', compact(
            'agent', 'money', 'recentRecharges', 'recentDeposits', 'counts', 'unread'
        ));
    }
}