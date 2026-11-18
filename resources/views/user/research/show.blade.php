@extends('layouts.app')
@section('title', 'Request #' . $r->id)
@section('content')

@php
    $stepNo = ['pending'=>1,'quoted'=>2,'accepted'=>3,'in_progress'=>4,'completed'=>5][$r->status] ?? 0;
    $steps = ['Requested','Quoted','Accepted','Paid','Completed'];
@endphp

<div class="page-heading">
    <div><h2>Request #{{ $r->id }}</h2><p>{{ $r->title }}</p></div>
    <div class="ag-actions">
        <a href="{{ route('user.agents.messages', $r->agent_id) }}" class="ag-btn ghost"><i class="fa-solid fa-comments"></i> Chat with Agent</a>
        <a href="{{ route('user.research.index') }}" class="ag-btn ghost">Back</a>
    </div>
</div>

@include('partials.agent-styles')
@include('partials.agent-flash')

<div class="ag-wrap">

    <div class="ag-panel">
        <div class="ag-head"><div><h3>Progress</h3></div><span class="ag-badge {{ $r->statusColor() }}">{{ $r->statusLabel() }}</span></div>
        @if($stepNo)
            <div class="ag-steps">
                @foreach($steps as $i => $s)<div class="ag-step {{ $stepNo >= $i + 1 ? 'done' : '' }}"><i></i>{{ $s }}</div>@endforeach
            </div>
        @else
            <div class="ag-notice err">This request was {{ $r->status }}. {{ $r->closed_reason }}</div>
        @endif
    </div>

    <div class="ag-panel">
        <div class="ag-head"><div><h3>Details</h3></div></div>
        <dl class="ag-kv" style="margin-bottom:16px;">
            <dt>Agent</dt><dd>{{ $r->agent?->user?->name }}</dd>
            <dt>Area</dt><dd>{{ $r->district?->name ?? '—' }}</dd>
            <dt>Sent</dt><dd>{{ $r->created_at->format('d M Y, h:i A') }}</dd>
        </dl>
        <div class="ag-text">{{ $r->description }}</div>
    </div>

    {{-- Quote --}}
    @if($r->status === 'quoted')
        <div class="ag-panel" style="border-color:#bfdbfe;background:#f8fbff;">
            <div class="ag-head"><div><h3>Agent's Quote</h3><p>Review the price and timeline.</p></div></div>
            <dl class="ag-kv" style="margin-bottom:16px;">
                <dt>Price</dt><dd><strong style="font-size:18px;">{{ $r->quoteText() }}</strong></dd>
                <dt>Time needed</dt><dd>{{ $r->quoted_days }} day(s)</dd>
                <dt>Note</dt><dd>{{ $r->quote_note ?: '—' }}</dd>
            </dl>
            <div class="ag-actions">
                <form method="POST" action="{{ route('user.research.accept', $r) }}">@csrf @method('PATCH')
                    <button class="ag-btn"><i class="fa-solid fa-check"></i> Accept Quote</button>
                </form>
                <form method="POST" action="{{ route('user.research.cancel', $r) }}" onsubmit="return confirm('Cancel this request?')">@csrf @method('PATCH')
                    <button class="ag-btn danger">Decline &amp; Cancel</button>
                </form>
            </div>
        </div>
    @endif

    {{-- Payment --}}
    @if($r->status === 'accepted')
        <div class="ag-panel">
            <div class="ag-head"><div><h3>Pay the Research Fee</h3><p>Amount: <strong>{{ $r->quoteText() }}</strong></p></div></div>

            <div class="ag-notice warn" style="margin-bottom:16px;">
                <i class="fa-solid fa-triangle-exclamation"></i>
                This payment is for the agent's <strong>research service only</strong>. It is <u>not</u> an investment deposit and will <u>not</u> add balance to your wallet.
            </div>

            @if($r->payment_submitted_at)
                <div class="ag-notice ok">
                    Payment reference submitted ({{ $r->payment_details }}, ref <strong>{{ $r->payment_reference }}</strong>).
                    Waiting for the agent to confirm.
                </div>
            @elseif($methods->count())
                <form method="POST" action="{{ route('user.research.payment', $r) }}">
                    @csrf @method('PATCH')
                    <div class="ag-field">
                        <label>Pay to *</label>
                        @foreach($methods as $m)
                            <label class="ag-geo ag-check" style="display:flex;margin-bottom:8px;">
                                <input type="radio" name="payment_method_id" value="{{ $m->id }}" required>
                                <span><strong>{{ $m->label() }}</strong> · {{ $m->account_number }}<br><small style="color:var(--muted);">{{ $m->account_name }}@if($m->bank_name) · {{ $m->bank_name }}@endif @if($m->note) · {{ $m->note }}@endif</small></span>
                            </label>
                        @endforeach
                    </div>
                    <div class="ag-field"><label>Transaction ID / Reference *</label><input type="text" name="payment_reference" maxlength="100" required></div>
                    <button class="ag-btn"><i class="fa-solid fa-receipt"></i> I have paid, submit reference</button>
                </form>
            @else
                <div class="ag-notice">The agent has not added payment details yet. Please message the agent.</div>
            @endif

            @unless($r->payment_submitted_at)
                <form method="POST" action="{{ route('user.research.cancel', $r) }}" onsubmit="return confirm('Cancel this request?')" style="margin-top:14px;">@csrf @method('PATCH')
                    <button class="ag-btn danger sm">Cancel Request</button>
                </form>
            @endunless
        </div>
    @endif

    @if($r->status === 'in_progress')
        <div class="ag-notice ok">
            <i class="fa-solid fa-spinner"></i> The agent confirmed your payment on {{ $r->payment_confirmed_at?->format('d M Y') }} and is working on it. Expected within {{ $r->quoted_days }} day(s).
        </div>
    @endif

    {{-- Report --}}
    @if($r->status === 'completed' && $r->submission)
        <div class="ag-panel">
            <div class="ag-head">
                <div><h3>{{ $r->submission->title }}</h3><p>Delivered {{ $r->submission->submitted_at?->format('d M Y, h:i A') }}</p></div>
                @if($r->submission->attachment_path)
                    <a class="ag-btn ghost sm" href="{{ route('user.research.download', $r) }}"><i class="fa-solid fa-download"></i> {{ $r->submission->attachment_name }}</a>
                @endif
            </div>
            <div class="ag-text">{{ $r->submission->report }}</div>
        </div>
    @endif
</div>
@endsection