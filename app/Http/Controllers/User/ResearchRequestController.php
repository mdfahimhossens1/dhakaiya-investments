<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\ResearchRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ResearchRequestController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $requests = $request->user()->researchRequests()
            ->with('agent.user:id,name')
            ->when(array_key_exists($status, ResearchRequest::STATUSES), fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('user.research.index', compact('requests', 'status'));
    }

    public function create(Agent $agent)
    {
        $this->assertListed($agent);
        $agent->load(['user:id,name', 'districts.country']);

        return view('user.research.create', compact('agent'));
    }

    public function store(Request $request, Agent $agent)
    {
        $this->assertListed($agent);
        $user = $request->user();

        $data = $request->validate([
            'title'             => ['required', 'string', 'max:150'],
            'description'       => ['required', 'string', 'min:20', 'max:3000'],
            'agent_district_id' => ['nullable', Rule::exists('agent_locations', 'agent_district_id')->where('agent_id', $agent->id)],
        ]);

        $open = ResearchRequest::where('user_id', $user->id)
            ->where('agent_id', $agent->id)
            ->whereIn('status', ['pending', 'quoted', 'accepted', 'in_progress'])
            ->count();

        if ($open >= 3) {
            return back()->withInput()->with('error', 'You already have 3 open requests with this agent.');
        }

        $r = ResearchRequest::create($data + [
            'user_id'  => $user->id,
            'agent_id' => $agent->id,
            'status'   => 'pending',
        ]);

        $r->say($user->id, 'New research request: ' . $r->title);

        return redirect()->route('user.research.show', $r)
            ->with('success', 'Request sent. The agent will reply with a price and timeline.');
    }

    public function show(Request $request, ResearchRequest $researchRequest)
    {
        $this->own($request, $researchRequest);

        $researchRequest->load(['agent.user:id,name', 'district', 'submission', 'paymentMethod']);

        $methods = $researchRequest->status === 'accepted'
            ? $researchRequest->agent->paymentMethods()->where('is_active', true)->get()
            : collect();

        return view('user.research.show', ['r' => $researchRequest, 'methods' => $methods]);
    }

    public function accept(Request $request, ResearchRequest $researchRequest)
    {
        $this->own($request, $researchRequest);

        $ok = ResearchRequest::guarded($researchRequest->id, ['quoted'], function ($r) {
            $r->update(['status' => 'accepted', 'accepted_at' => now()]);
            $r->say($r->user_id, 'I accept your quote. Please share the payment details.');
        });

        return back()->with($ok ? 'success' : 'error', $ok ? 'Quote accepted. Now pay the agent and submit your payment reference.' : 'This quote can no longer be accepted.');
    }

    public function cancel(Request $request, ResearchRequest $researchRequest)
    {
        $this->own($request, $researchRequest);

        $ok = ResearchRequest::guarded($researchRequest->id, ['pending', 'quoted', 'accepted'], function ($r) {
            // payment submit korle ar cancel nai, agent er sathe kotha bolte hobe
            if ($r->payment_submitted_at) return false;

            $r->update(['status' => 'cancelled', 'closed_reason' => 'Cancelled by user']);
        });

        return back()->with($ok ? 'success' : 'error', $ok ? 'Request cancelled.' : 'This request cannot be cancelled now. Please message the agent.');
    }

    /** User bole je se research fee pathiyeche. Eta deposit na, wallet e kichu jay na */
    public function submitPayment(Request $request, ResearchRequest $researchRequest)
    {
        $this->own($request, $researchRequest);

        $data = $request->validate([
            'payment_method_id' => [
                'required',
                Rule::exists('agent_payment_methods', 'id')
                    ->where('agent_id', $researchRequest->agent_id)
                    ->where('is_active', true),
            ],
            'payment_reference' => ['required', 'string', 'max:100'],
        ]);

        $ok = ResearchRequest::guarded($researchRequest->id, ['accepted'], function ($r) use ($data) {
            $method = $r->agent->paymentMethods()->findOrFail($data['payment_method_id']);

            $r->update([
                'payment_method_id'    => $method->id,
                'payment_details'      => $method->snapshot(),
                'payment_reference'    => $data['payment_reference'],
                'payment_submitted_at' => now(),
            ]);

            $r->say($r->user_id, 'I have sent the research fee. Reference: ' . $data['payment_reference']);
        });

        return back()->with($ok ? 'success' : 'error', $ok ? 'Payment reference submitted. Waiting for the agent to confirm.' : 'Payment cannot be submitted in the current status.');
    }

    public function downloadReport(Request $request, ResearchRequest $researchRequest)
    {
        $this->own($request, $researchRequest);
        $s = $researchRequest->submission;

        abort_unless($s && $s->attachment_path && Storage::disk('local')->exists($s->attachment_path), 404);

        return Storage::disk('local')->download($s->attachment_path, $s->attachment_name);
    }

    private function own(Request $request, ResearchRequest $r): void
    {
        abort_unless((int) $r->user_id === (int) $request->user()->id, 404);
    }

    private function assertListed(Agent $agent): void
    {
        abort_unless(Agent::listed()->whereKey($agent->id)->exists(), 404);
    }
}