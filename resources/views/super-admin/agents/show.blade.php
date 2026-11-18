@extends('layouts.app')
@section('title', 'Agent Details')
@section('content')

<div class="page-heading">
    <div><h2>{{ $agent->user->name }}</h2><p>Agent details, verification and performance.</p></div>
    <a href="{{ route('super-admin.agents.edit', $agent) }}" class="ag-btn ghost"><i class="fa-solid fa-pen"></i> Edit</a>
</div>

@include('partials.agent-styles')
@include('partials.agent-flash')

@php
    $total = $counts->sum();
    $done = (int) ($counts['completed'] ?? 0);
@endphp

<div class="ag-wrap">

    {{-- Controls --}}
    <div class="ag-panel">
        <div class="ag-head">
            <div>
                <h3>Verification &amp; Status</h3>
                <p>An agent appears to users only when approved, verified, active and available.</p>
            </div>
            <div class="ag-actions">
                <span class="ag-badge {{ ['approved'=>'green','pending'=>'amber','rejected'=>'red'][$agent->approval_status] ?? 'gray' }}">{{ ucfirst($agent->approval_status) }}</span>
                <span class="ag-badge {{ $agent->is_verified ? 'blue' : 'gray' }}">{{ $agent->is_verified ? 'Verified' : 'Not verified' }}</span>
                <span class="ag-badge {{ $agent->is_active ? 'green' : 'red' }}">{{ $agent->is_active ? 'Active' : 'Inactive' }}</span>
                <span class="ag-badge {{ $agent->is_available ? 'teal' : 'gray' }}">{{ $agent->is_available ? 'Available' : 'Unavailable' }}</span>
            </div>
        </div>

        <div class="ag-actions">
            <form method="POST" action="{{ route('super-admin.agents.verify', $agent) }}">@csrf @method('PATCH')
                <button class="ag-btn {{ $agent->is_verified ? 'warn' : '' }}">
                    <i class="fa-solid fa-badge-check"></i> {{ $agent->is_verified ? 'Remove Verification' : 'Verify Agent' }}
                </button>
            </form>

            @if($agent->approval_status !== 'approved')
                <form method="POST" action="{{ route('super-admin.agents.approval', $agent) }}">@csrf @method('PATCH')
                    <input type="hidden" name="approval_status" value="approved">
                    <button class="ag-btn ghost"><i class="fa-solid fa-check"></i> Approve</button>
                </form>
            @endif
            @if($agent->approval_status !== 'rejected')
                <form method="POST" action="{{ route('super-admin.agents.approval', $agent) }}" onsubmit="return confirm('Reject this agent?')">@csrf @method('PATCH')
                    <input type="hidden" name="approval_status" value="rejected">
                    <button class="ag-btn danger"><i class="fa-solid fa-xmark"></i> Reject</button>
                </form>
            @endif

            <form method="POST" action="{{ route('super-admin.agents.status', $agent) }}">@csrf @method('PATCH')
                <button class="ag-btn {{ $agent->is_active ? 'danger' : 'ghost' }}">
                    {{ $agent->is_active ? 'Deactivate' : 'Activate' }}
                </button>
            </form>
        </div>
    </div>

    <div class="ag-grid ag-g4">
        <div class="ag-card ag-stat"><div class="ag-ico"><i class="fa-solid fa-inbox"></i></div><div><h3>{{ $total }}</h3><p>Total Requests</p></div></div>
        <div class="ag-card ag-stat"><div class="ag-ico amber"><i class="fa-solid fa-clock"></i></div><div><h3>{{ ($counts['pending'] ?? 0) + ($counts['quoted'] ?? 0) }}</h3><p>Waiting</p></div></div>
        <div class="ag-card ag-stat"><div class="ag-ico purple"><i class="fa-solid fa-spinner"></i></div><div><h3>{{ ($counts['accepted'] ?? 0) + ($counts['in_progress'] ?? 0) }}</h3><p>Active</p></div></div>
        <div class="ag-card ag-stat"><div class="ag-ico green"><i class="fa-solid fa-circle-check"></i></div><div><h3>{{ $done }}</h3><p>Completed</p></div></div>
    </div>

    <div class="ag-grid ag-g2">
        <div class="ag-panel">
            <div class="ag-head"><div><h3>Profile</h3></div></div>
            <dl class="ag-kv">
                <dt>Email</dt><dd>{{ $agent->user->email }}</dd>
                <dt>Phone</dt><dd>{{ $agent->phone ?: '—' }}</dd>
                <dt>WhatsApp</dt><dd>{{ $agent->whatsapp ?: '—' }}</dd>
                <dt>Bio</dt><dd>{{ $agent->bio ?: '—' }}</dd>
                <dt>Joined</dt><dd>{{ $agent->created_at->format('d M Y') }}</dd>
                <dt>Verified at</dt><dd>{{ $agent->verified_at?->format('d M Y') ?? '—' }}</dd>
            </dl>
        </div>

        <div class="ag-panel">
            <div class="ag-head"><div><h3>Assigned Areas</h3></div></div>
            @forelse($agent->districts->groupBy(fn ($d) => $d->country->name) as $country => $list)
                <p style="margin:0 0 8px;font-size:13px;"><strong>{{ $country }}:</strong> {{ $list->pluck('name')->implode(', ') }}</p>
            @empty
                <div class="ag-empty" style="padding:20px;">No areas assigned.</div>
            @endforelse

            <div class="ag-head" style="margin-top:20px;"><div><h3>Payment Methods</h3><p>For research fee only. Not deposit.</p></div></div>
            @forelse($agent->paymentMethods as $pm)
                <p style="margin:0 0 6px;font-size:13px;">
                    {{ $pm->snapshot() }}
                    @unless($pm->is_active)<span class="ag-badge gray">off</span>@endunless
                </p>
            @empty
                <div class="ag-empty" style="padding:14px;">None added.</div>
            @endforelse
        </div>
    </div>

    <div class="ag-panel">
        <div class="ag-head">
            <div><h3>Recent Requests</h3></div>
            <a class="ag-btn ghost sm" href="{{ route('super-admin.research-requests.index', ['agent' => $agent->id]) }}">View all</a>
        </div>
        @if($recent->count())
            <div class="ag-table-wrap">
                <table class="ag-table">
                    <thead><tr><th>#</th><th>Title</th><th>User</th><th>Quote</th><th>Status</th><th></th></tr></thead>
                    <tbody>
                    @foreach($recent as $r)
                        <tr>
                            <td>{{ $r->id }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($r->title, 40) }}</td>
                            <td>{{ $r->user?->name }}</td>
                            <td>{{ $r->quoteText() ?? '—' }}</td>
                            <td><span class="ag-badge {{ $r->statusColor() }}">{{ $r->statusLabel() }}</span></td>
                            <td class="num"><a class="ag-btn ghost sm" href="{{ route('super-admin.research-requests.show', $r) }}">Open</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="ag-empty"><i class="fa-solid fa-inbox"></i>No requests yet.</div>
        @endif
    </div>
</div>
@endsection