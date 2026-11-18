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
        <a href="{{ route('agent.messages.show', $r->user_id) }}" class="ag-btn ghost"><i class="fa-solid fa-comments"></i> Message User</a>
        <a href="{{ route('agent.requests.index') }}" class="ag-btn ghost">Back</a>
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
        <div class="ag-head"><div><h3>Request Details</h3></div></div>
        <dl class="ag-kv" style="margin-bottom:16px;">
            <dt>User</dt><dd>{{ $r->user?->name }}</dd>
            <dt>Area</dt><dd>{{ $r->district?->name ?? '—' }}</dd>
            <dt>Received</dt><dd>{{ $r->created_at->format('d M Y, h:i A') }}</dd>
            @if($r->quoted_price !== null)
                <dt>Your quote</dt><dd><strong>{{ $r->quoteText() }}</strong> · {{ $r->quoted_days }} day(s)</dd>
            @endif
        </dl>
        <div class="ag-text">{{ $r->description }}</div>
    </div>

    {{-- 1. Quote --}}
    @if(in_array($r->status, ['pending','quoted']))
        <div class="ag-grid ag-g2">
            <div class="ag-panel">
                <div class="ag-head"><div><h3>{{ $r->status === 'quoted' ? 'Update Quote' : 'Send Quote' }}</h3><p>Tell the user the price and time needed.</p></div></div>
                <form method="POST" action="{{ route('agent.requests.quote', $r) }}">
                    @csrf
                    <div class="ag-row3">
                        <div class="ag-field"><label>Price *</label><input type="number" step="0.01" min="1" name="quoted_price" value="{{ old('quoted_price', $r->quoted_price) }}" required></div>
                        <div class="ag-field">
                            <label>Currency *</label>
                            <select name="quoted_currency">
                                @foreach($currencies as $c)<option value="{{ $c }}" @selected(old('quoted_currency', $r->quoted_currency ?? 'BDT') === $c)>{{ $c }}</option>@endforeach
                            </select>
                        </div>
                        <div class="ag-field"><label>Days *</label><input type="number" min="1" max="90" name="quoted_days" value="{{ old('quoted_days', $r->quoted_days) }}" required></div>
                    </div>
                    <div class="ag-field"><label>Note</label><textarea name="quote_note" maxlength="1000" style="min-height:70px;">{{ old('quote_note', $r->quote_note) }}</textarea></div>
                    <button class="ag-btn"><i class="fa-solid fa-paper-plane"></i> Send Quote</button>
                </form>
            </div>

            <div class="ag-panel">
                <div class="ag-head"><div><h3>Decline Request</h3><p>If you cannot take this request.</p></div></div>
                <form method="POST" action="{{ route('agent.requests.reject', $r) }}" onsubmit="return confirm('Reject this request?')">
                    @csrf @method('PATCH')
                    <div class="ag-field"><label>Reason *</label><input type="text" name="closed_reason" maxlength="255" required></div>
                    <button class="ag-btn danger">Reject Request</button>
                </form>
            </div>
        </div>
    @endif

    {{-- 2. Payment confirm --}}
    @if($r->status === 'accepted')
        <div class="ag-panel">
            <div class="ag-head"><div><h3>Confirm Research Fee</h3><p>The user accepted your quote of {{ $r->quoteText() }}.</p></div></div>

            @if($r->payment_submitted_at)
                <div class="ag-notice warn" style="margin-bottom:14px;">
                    The user says they paid via <strong>{{ $r->payment_details }}</strong>.
                    Reference: <strong>{{ $r->payment_reference }}</strong>. Check it in your own account before confirming.
                </div>
            @else
                <div class="ag-notice" style="margin-bottom:14px;">The user has not submitted a payment reference yet. Confirm only after you actually receive the fee.</div>
            @endif

            <form method="POST" action="{{ route('agent.requests.confirm-payment', $r) }}">
                @csrf @method('PATCH')
                <label class="ag-check" style="margin-bottom:14px;">
                    <input type="checkbox" name="received" value="1"> I have received the research fee of {{ $r->quoteText() }}
                </label>
                <button class="ag-btn"><i class="fa-solid fa-circle-check"></i> Confirm Payment</button>
            </form>
            <p style="font-size:12px;color:var(--muted);margin:12px 0 0;">This confirms a <strong>research service fee</strong> only. It does not add anything to the user's investment wallet.</p>
        </div>
    @endif

    {{-- 3. Report --}}
    @if($r->status === 'in_progress')
        <div class="ag-panel">
            <div class="ag-head"><div><h3>Submit Research Report</h3><p>Payment confirmed {{ $r->payment_confirmed_at?->format('d M Y') }}. Deadline: {{ $r->quoted_days }} day(s).</p></div></div>
            <form method="POST" action="{{ route('agent.requests.report', $r) }}" enctype="multipart/form-data">
                @csrf
                <div class="ag-field"><label>Report title *</label><input type="text" name="title" value="{{ old('title', $r->title) }}" maxlength="150" required></div>
                <div class="ag-field"><label>Report *</label><textarea name="report" style="min-height:220px;" required>{{ old('report') }}</textarea></div>
                <div class="ag-field"><label>Attachment</label><input type="file" name="attachment" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"><small>PDF, Word or image. Max 5 MB.</small></div>
                <button class="ag-btn"><i class="fa-solid fa-file-circle-check"></i> Submit Report</button>
            </form>
        </div>
    @endif

    @if($r->status === 'completed' && $r->submission)
        <div class="ag-panel">
            <div class="ag-head">
                <div><h3>{{ $r->submission->title }}</h3><p>Submitted {{ $r->submission->submitted_at?->format('d M Y, h:i A') }}</p></div>
                @if($r->submission->attachment_path)
                    <a class="ag-btn ghost sm" href="{{ route('agent.requests.download', $r) }}"><i class="fa-solid fa-download"></i> {{ $r->submission->attachment_name }}</a>
                @endif
            </div>
            <div class="ag-text">{{ $r->submission->report }}</div>
        </div>
    @endif
</div>
@endsection