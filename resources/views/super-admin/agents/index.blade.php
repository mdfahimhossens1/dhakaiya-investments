@extends('layouts.app')
@section('title', 'Agents')
@section('content')

<div class="page-heading">
    <div><h2>Agents</h2><p>Create, verify and control local agents.</p></div>
    <a href="{{ route('super-admin.agents.create') }}" class="ag-btn"><i class="fa-solid fa-plus"></i> New Agent</a>
</div>

@include('partials.agent-styles')
@include('partials.agent-flash')

<div class="ag-wrap">
    <div class="ag-grid ag-g4">
        <div class="ag-card ag-stat"><div class="ag-ico"><i class="fa-solid fa-user-tie"></i></div><div><h3>{{ $stats['total'] }}</h3><p>Total Agents</p></div></div>
        <div class="ag-card ag-stat"><div class="ag-ico amber"><i class="fa-solid fa-hourglass-half"></i></div><div><h3>{{ $stats['pending'] }}</h3><p>Pending Approval</p></div></div>
        <div class="ag-card ag-stat"><div class="ag-ico blue"><i class="fa-solid fa-circle-check"></i></div><div><h3>{{ $stats['verified'] }}</h3><p>Verified</p></div></div>
        <div class="ag-card ag-stat"><div class="ag-ico green"><i class="fa-solid fa-user-check"></i></div><div><h3>{{ $stats['active'] }}</h3><p>Active</p></div></div>
    </div>

    <div class="ag-panel">
        <form method="GET" class="ag-filter" style="margin-bottom:18px;">
            <div class="ag-field"><label>Search</label><input type="text" name="q" value="{{ $q }}" placeholder="Name or email"></div>
            <div class="ag-field">
                <label>Approval</label>
                <select name="approval">
                    <option value="">All</option>
                    @foreach(['pending','approved','rejected'] as $o)
                        <option value="{{ $o }}" @selected($approval === $o)>{{ ucfirst($o) }}</option>
                    @endforeach
                </select>
            </div>
            <button class="ag-btn">Filter</button>
        </form>

        @if($agents->count())
            <div class="ag-table-wrap">
                <table class="ag-table">
                    <thead>
                        <tr><th>Agent</th><th>Areas</th><th>Approval</th><th>Verified</th><th>Status</th><th class="num">Requests</th><th>Quick actions</th><th></th></tr>
                    </thead>
                    <tbody>
                    @foreach($agents as $a)
                        <tr>
                            <td>{{ $a->user->name ?? 'Deleted user' }}<small>{{ $a->user->email ?? '' }}</small><small>{{ $a->user->phone ?? '' }}</small></td>
                            <td>
                                {{ $a->districts->take(3)->pluck('name')->implode(', ') ?: '—' }}
                                @if($a->districts->count() > 3) <small>+{{ $a->districts->count() - 3 }} more</small> @endif
                            </td>
                            <td><span class="ag-badge {{ ['approved'=>'green','pending'=>'amber','rejected'=>'red'][$a->approval_status] ?? 'gray' }}">{{ ucfirst($a->approval_status) }}</span></td>
                            <td>@if($a->is_verified)<span class="ag-badge blue">Verified</span>@else<span class="ag-badge gray">Not verified</span>@endif</td>
                            <td>@if($a->is_active)<span class="ag-badge green">Active</span>@else<span class="ag-badge red">Inactive</span>@endif</td>
                            <td class="num">{{ $a->requests_count }}</td>
                            <td>
                                <div style="display:flex;gap:6px;flex-wrap:wrap">
                                    <form method="POST" action="{{ route('super-admin.agents.verify', $a) }}">
                                        @csrf @method('PATCH')
                                        <button class="ag-btn ghost sm">{{ $a->is_verified ? 'Unverify' : 'Verify' }}</button>
                                    </form>
                                    @if($a->approval_status !== 'approved')
                                        <form method="POST" action="{{ route('super-admin.agents.approval', $a) }}">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="approval_status" value="approved">
                                            <button class="ag-btn ghost sm">Approve</button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('super-admin.agents.status', $a) }}">
                                        @csrf @method('PATCH')
                                        <button class="ag-btn ghost sm">{{ $a->is_active ? 'Deactivate' : 'Activate' }}</button>
                                    </form>
                                </div>
                            </td>
                            <td class="num">
                                <a class="ag-btn ghost sm" href="{{ route('super-admin.agents.show', $a) }}">View</a>
                                <a class="ag-btn ghost sm" href="{{ route('super-admin.agents.edit', $a) }}">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div style="margin-top:16px;">{{ $agents->links() }}</div>
        @else
            <div class="ag-empty"><i class="fa-solid fa-user-tie"></i>No agents found.</div>
        @endif
    </div>
</div>
@endsection