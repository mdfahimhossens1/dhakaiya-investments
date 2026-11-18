<?php

namespace App\Http\Controllers\Agent;

use App\Models\AgentMessage;

class DashboardController extends BaseAgentController
{
    public function index()
    {
        $agent = $this->agent()->load('districts.country');

        $counts = $agent->requests()
            ->selectRaw('status, COUNT(*) as c')
            ->groupBy('status')
            ->pluck('c', 'status');

        $unread = AgentMessage::where('agent_id', $agent->id)
            ->where('sender_id', '!=', $agent->user_id)
            ->whereNull('read_at')
            ->count();

        $recent = $agent->requests()->with('user:id,name')->latest()->limit(6)->get();

        return view('agent.dashboard', compact('agent', 'counts', 'unread', 'recent'));
    }
}