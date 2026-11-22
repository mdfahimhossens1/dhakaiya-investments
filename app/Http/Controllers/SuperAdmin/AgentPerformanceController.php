<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\ResearchRequest;

class AgentPerformanceController extends Controller
{
    public function index()
    {
        $agents = Agent::with('user:id,name')
            ->withCount([
                'requests',
                'requests as completed_count' => fn ($q) => $q->where('status', 'completed'),
                'requests as rejected_count'  => fn ($q) => $q->where('status', 'rejected'),
                'requests as active_count'    => fn ($q) => $q->whereIn('status', ['accepted', 'in_progress']),
                'requests as waiting_count'   => fn ($q) => $q->whereIn('status', ['pending', 'quoted']),
                'recharges as recharge_count' => fn ($q) => $q->where('status', 'completed'),
            ])
            ->withSum(['recharges as total_recharged' => fn ($q) => $q->where('status', 'completed')], 'amount')
            ->withSum(['deposits as total_deposited'  => fn ($q) => $q->where('status', 'approved')], 'amount')
            ->get()
            ->sortByDesc('total_recharged')
            ->values();

        // গড় completion সময় (দিন), এক query-তে
        $avgDays = ResearchRequest::where('status', 'completed')
            ->whereNotNull('completed_at')
            ->get(['agent_id', 'created_at', 'completed_at'])
            ->groupBy('agent_id')
            ->map(fn ($g) => round($g->avg(fn ($r) => $r->created_at->diffInHours($r->completed_at)) / 24, 1));

        return view('super-admin.agent-performance.index', compact('agents', 'avgDays'));
    }
}