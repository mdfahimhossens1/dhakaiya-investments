<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\ResearchRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AgentResearchController extends Controller
{
    public function index(Request $request)
    {
        return $this->listing($request, false);
    }

    /** Submit howa report gulo (completed request) */
    public function reports(Request $request)
    {
        return $this->listing($request, true);
    }

    public function show(ResearchRequest $researchRequest)
    {
        $researchRequest->load(['user:id,name,email', 'agent.user:id,name', 'district', 'submission']);

        return view('super-admin.research-requests.show', ['r' => $researchRequest]);
    }

    public function download(ResearchRequest $researchRequest)
    {
        $s = $researchRequest->submission;

        abort_unless($s && $s->attachment_path && Storage::disk('local')->exists($s->attachment_path), 404);

        return Storage::disk('local')->download($s->attachment_path, $s->attachment_name);
    }

    private function listing(Request $request, bool $reports)
    {
        $status = $request->query('status');
        $agentId = (int) $request->query('agent');
        $q = trim((string) $request->query('q'));

        $requests = ResearchRequest::with(['user:id,name', 'agent.user:id,name', 'submission:id,research_request_id,submitted_at'])
            ->when($reports, fn ($x) => $x->where('status', 'completed'))
            ->when(! $reports && array_key_exists($status, ResearchRequest::STATUSES), fn ($x) => $x->where('status', $status))
            ->when($agentId, fn ($x) => $x->where('agent_id', $agentId))
            ->when($q !== '', fn ($x) => $x->where('title', 'like', "%{$q}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $agents = Agent::with('user:id,name')->get()->sortBy(fn ($a) => $a->user?->name);

        return view('super-admin.research-requests.index', [
            'requests' => $requests,
            'agents'   => $agents,
            'reports'  => $reports,
            'status'   => $status,
            'agentId'  => $agentId,
            'q'        => $q,
        ]);
    }
}