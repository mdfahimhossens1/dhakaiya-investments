@extends('layouts.app')
@section('title', 'Research Request #' . $r->id)
@section('content')

<div class="page-heading">
    <div><h2>Request #{{ $r->id }}</h2><p>{{ $r->title }}</p></div>
    <a href="{{ route('super-admin.research-requests.index') }}" class="ag-btn ghost">Back</a>
</div>

@include('partials.agent-styles')
@include('partials.agent-flash')

<div class="ag-wrap">
    <div class="ag-panel">
        <div class="ag-head"><div><h3>Overview</h3></div><span class="ag-badge {{ $r->statusColor() }}">{{ $r->statusLabel() }}</span></div>
        <dl class="ag-kv">
            <dt>User</dt><dd>{{ $r->user?->name }} ({{ $r->user?->email }})</dd>
            <dt>Agent</dt><dd><a href="{{ route('super-admin.agents.show', $r->agent) }}">{{ $r->agent?->user?->name }}</a></dd>
            <dt>Area</dt><dd>{{ $r->district?->name ?? '—' }}</dd>
            <dt>Created</dt><dd>{{ $r->created_at->format('d M Y, h:i A') }}</dd>
            <dt>Quote</dt><dd>{{ $r->quoteText() ?? '—' }} @if($r->quoted_days)· {{ $r->quoted_days }} day(s)@endif</dd>
            <dt>Quote note</dt><dd>{{ $r->quote_note ?: '—' }}</dd>
            <dt>Payment</dt><dd>{{ $r->payment_details ?: '—' }}</dd>
            <dt>Reference</dt><dd>{{ $r->payment_reference ?: '—' }}</dd>
            <dt>Paid confirmed</dt><dd>{{ $r->payment_confirmed_at?->format('d M Y, h:i A') ?? '—' }}</dd>
            <dt>Completed</dt><dd>{{ $r->completed_at?->format('d M Y, h:i A') ?? '—' }}</dd>
            @if($r->closed_reason)<dt>Closed reason</dt><dd>{{ $r->closed_reason }}</dd>@endif
        </dl>
    </div>

    <div class="ag-panel">
        <div class="ag-head"><div><h3>Request</h3></div></div>
        <div class="ag-text">{{ $r->description }}</div>
    </div>

    @if($r->submission)
        <div class="ag-panel">
            <div class="ag-head">
                <div><h3>{{ $r->submission->title }}</h3><p>Submitted {{ $r->submission->submitted_at?->format('d M Y, h:i A') }}</p></div>
                @if($r->submission->attachment_path)
                    <a class="ag-btn ghost sm" href="{{ route('super-admin.research-requests.download', $r) }}"><i class="fa-solid fa-download"></i> {{ $r->submission->attachment_name }}</a>
                @endif
            </div>
            <div class="ag-text">{{ $r->submission->report }}</div>
        </div>
    @endif

    <div class="ag-notice">
        <i class="fa-solid fa-circle-info"></i>
        Research fee is recorded here for reference only. It is separate from the user's investment wallet and never creates a deposit.
    </div>
</div>
@endsection