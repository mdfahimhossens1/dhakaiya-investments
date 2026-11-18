<?php

namespace App\Http\Controllers\Agent;

use App\Models\ResearchRequest;
use App\Models\ResearchSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class RequestController extends BaseAgentController
{
    private const FILTERS = [
        'all'         => null,
        'pending'     => ['pending'],
        'quoted'      => ['quoted'],
        'accepted'    => ['accepted'],
        'in_progress' => ['in_progress'],
        'completed'   => ['completed'],
        'history'     => ['completed', 'rejected', 'cancelled'],
    ];

    public function index(Request $request)
    {
        $agent = $this->agent();
        $status = $request->query('status', 'all');
        if (! array_key_exists($status, self::FILTERS)) $status = 'all';

        $requests = $agent->requests()
            ->with('user:id,name')
            ->when(self::FILTERS[$status], fn ($q, $list) => $q->whereIn('status', $list))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('agent.requests.index', compact('requests', 'status'));
    }

    public function show(ResearchRequest $researchRequest)
    {
        $this->own($researchRequest);
        $researchRequest->load(['user:id,name', 'district', 'submission', 'paymentMethod']);

        return view('agent.requests.show', [
            'r'          => $researchRequest,
            'currencies' => ResearchRequest::CURRENCIES,
        ]);
    }

    public function quote(Request $request, ResearchRequest $researchRequest)
    {
        $agent = $this->operational();
        $this->own($researchRequest);

        $data = $request->validate([
            'quoted_price'    => ['required', 'numeric', 'min:1', 'max:99999999'],
            'quoted_currency' => ['required', Rule::in(ResearchRequest::CURRENCIES)],
            'quoted_days'     => ['required', 'integer', 'min:1', 'max:90'],
            'quote_note'      => ['nullable', 'string', 'max:1000'],
        ]);

        $ok = ResearchRequest::guarded($researchRequest->id, ['pending', 'quoted'], function ($r) use ($data, $agent) {
            $r->update($data + ['status' => 'quoted', 'quoted_at' => now()]);
            $r->say($agent->user_id, 'Quote: ' . $r->quoteText() . ' · ' . $r->quoted_days . ' day(s).'
                . ($r->quote_note ? "\n" . $r->quote_note : ''));
        });

        return back()->with($ok ? 'success' : 'error', $ok ? 'Quote sent to the user.' : 'This request can no longer be quoted.');
    }

    public function reject(Request $request, ResearchRequest $researchRequest)
    {
        $agent = $this->operational();
        $this->own($researchRequest);

        $data = $request->validate(['closed_reason' => ['required', 'string', 'max:255']]);

        $ok = ResearchRequest::guarded($researchRequest->id, ['pending', 'quoted'], function ($r) use ($data, $agent) {
            $r->update(['status' => 'rejected', 'closed_reason' => $data['closed_reason']]);
            $r->say($agent->user_id, 'I cannot take this request: ' . $data['closed_reason']);
        });

        return back()->with($ok ? 'success' : 'error', $ok ? 'Request rejected.' : 'This request can no longer be rejected.');
    }

    /** Agent nije research fee pawar kotha nishchit kore. Wallet e kichu jay na */
    public function confirmPayment(Request $request, ResearchRequest $researchRequest)
    {
        $agent = $this->operational();
        $this->own($researchRequest);

        $request->validate(['received' => ['accepted']], [
            'received.accepted' => 'Please confirm that you have received the research fee.',
        ]);

        $ok = ResearchRequest::guarded($researchRequest->id, ['accepted'], function ($r) use ($agent) {
            $r->update(['status' => 'in_progress', 'payment_confirmed_at' => now()]);
            $r->say($agent->user_id, 'Payment received. I have started working on your research.');
        });

        return back()->with($ok ? 'success' : 'error', $ok ? 'Payment confirmed. You can now work on the research.' : 'Payment cannot be confirmed in the current status.');
    }

    public function submitReport(Request $request, ResearchRequest $researchRequest)
    {
        $agent = $this->operational();
        $this->own($researchRequest);

        $data = $request->validate([
            'title'      => ['required', 'string', 'max:150'],
            'report'     => ['required', 'string', 'min:30', 'max:20000'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:5120'],
        ]);

        $path = $name = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('research-reports');           // private disk
            $name = $file->getClientOriginalName();
        }

        $ok = ResearchRequest::guarded($researchRequest->id, ['in_progress'], function ($r) use ($data, $agent, $path, $name) {
            ResearchSubmission::create([
                'research_request_id' => $r->id,
                'agent_id'            => $agent->id,
                'title'               => $data['title'],
                'report'              => $data['report'],
                'attachment_path'     => $path,
                'attachment_name'     => $name,
                'submitted_at'        => now(),
            ]);

            $r->update(['status' => 'completed', 'completed_at' => now()]);
            $r->say($agent->user_id, 'Your research report is ready. Open the request to read it.');
        });

        if (! $ok && $path) {
            Storage::disk('local')->delete($path);
        }

        return back()->with($ok ? 'success' : 'error', $ok ? 'Report submitted.' : 'Report can only be submitted after payment is confirmed.');
    }

    public function download(ResearchRequest $researchRequest)
    {
        $this->own($researchRequest);
        $s = $researchRequest->submission;

        abort_unless($s && $s->attachment_path && Storage::disk('local')->exists($s->attachment_path), 404);

        return Storage::disk('local')->download($s->attachment_path, $s->attachment_name);
    }

    private function own(ResearchRequest $r): void
    {
        abort_unless((int) $r->agent_id === (int) $this->agent()->id, 404);
    }
}