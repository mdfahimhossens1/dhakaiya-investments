@extends('layouts.app')
@section('title', 'Agent Dashboard')
@section('content')

<div class="agent-page">
<div class="page-heading">
    <div><h2>Welcome, {{ auth()->user()->name }}</h2><p>Your research requests and messages at a glance.</p></div>
    <form method="POST" action="{{ route('agent.availability') }}">@csrf @method('PATCH')
        <button class="ag-btn {{ $agent->is_available ? 'ghost' : '' }}">
            <i class="fa-solid fa-power-off"></i> {{ $agent->is_available ? 'Available (click to go offline)' : 'Unavailable (click to go online)' }}
        </button>
    </form>
</div>

@include('partials.agent-styles')
@include('partials.agent-flash')

<div class="ag-wrap">

    @if(! $agent->isOperational())
        <div class="ag-notice err"><i class="fa-solid fa-ban"></i> Your agent account is currently <strong>{{ $agent->approval_status !== 'approved' ? $agent->approval_status : 'inactive' }}</strong>. You cannot reply or work on requests until the admin enables it.</div>
    @elseif(! $agent->is_verified)
        <div class="ag-notice warn"><i class="fa-solid fa-badge-check"></i> Your account is not verified yet. Users cannot find you until the admin verifies you.</div>
    @endif

    <div class="ag-grid ag-g4">
        <div class="ag-card ag-stat"><div class="ag-ico amber"><i class="fa-solid fa-clock"></i></div><div><h3>{{ $counts['pending'] ?? 0 }}</h3><p>Pending Requests</p></div></div>
        <div class="ag-card ag-stat"><div class="ag-ico purple"><i class="fa-solid fa-spinner"></i></div><div><h3>{{ ($counts['accepted'] ?? 0) + ($counts['in_progress'] ?? 0) }}</h3><p>In Progress</p></div></div>
        <div class="ag-card ag-stat"><div class="ag-ico green"><i class="fa-solid fa-circle-check"></i></div><div><h3>{{ $counts['completed'] ?? 0 }}</h3><p>Completed Research</p></div></div>
        <a href="{{ route('agent.messages.index') }}" class="ag-card ag-stat" style="text-decoration:none;"><div class="ag-ico blue"><i class="fa-solid fa-envelope"></i></div><div><h3>{{ $unread }}</h3><p>Unread Messages</p></div></a>
    </div>

    <div class="ag-grid ag-g2">
        <div class="ag-panel">
            <div class="ag-head"><div><h3>Assigned Areas</h3></div></div>
            @forelse($agent->districts->groupBy(fn ($d) => $d->country->name) as $country => $list)
                <p style="margin:0 0 8px;font-size:13px;"><strong>{{ $country }}:</strong> {{ $list->pluck('name')->implode(', ') }}</p>
            @empty
                <div class="ag-empty" style="padding:20px;">No areas assigned yet.</div>
            @endforelse
        </div>
        <div class="ag-panel">
            <div class="ag-head"><div><h3>Status</h3></div></div>
            <p style="margin:0 0 10px;">
                <span class="ag-badge {{ $agent->is_verified ? 'blue' : 'gray' }}">{{ $agent->is_verified ? 'Verified' : 'Not verified' }}</span>
                <span class="ag-badge {{ $agent->is_available ? 'teal' : 'gray' }}">{{ $agent->is_available ? 'Available' : 'Unavailable' }}</span>
            </p>
            <p style="font-size:12.5px;color:var(--muted);margin:0;">Add your payment methods in <a href="{{ route('agent.profile.index') }}">My Profile</a> so users know where to pay the research fee.</p>
        </div>
    </div>

    <div class="ag-panel">
        <div class="ag-head"><div><h3>Recent Requests</h3></div><a class="ag-btn ghost sm" href="{{ route('agent.requests.index') }}">View all</a></div>
        @if($recent->count())
            <div class="ag-table-wrap">
                <table class="ag-table">
                    <thead><tr><th>#</th><th>Title</th><th>User</th><th>Status</th><th></th></tr></thead>
                    <tbody>
                    @foreach($recent as $r)
                        <tr>
                            <td>{{ $r->id }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($r->title, 45) }}<small>{{ $r->created_at->diffForHumans() }}</small></td>
                            <td>{{ $r->user?->name }}</td>
                            <td><span class="ag-badge {{ $r->statusColor() }}">{{ $r->statusLabel() }}</span></td>
                            <td class="num"><a class="ag-btn ghost sm" href="{{ route('agent.requests.show', $r) }}">Open</a></td>
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
</div>
@endsection