@extends('layouts.app')
@section('title', 'Agent Deposits')
@section('content')

@php $color = ['pending' => 'amber', 'approved' => 'green', 'rejected' => 'red']; @endphp

<div class="page-heading">
    <div><h2>Agent Deposits</h2><p>Verify and approve agent recharge-balance deposits.</p></div>
    <a class="ag-btn ghost" href="{{ route('super-admin.agents.index') }}"><i class="fa-solid fa-user-tie"></i> Agents</a>
</div>

@include('partials.agent-styles')
@include('partials.agent-flash')

<div class="ag-wrap">
    <div class="ag-grid ag-g4">
        <div class="ag-card ag-stat"><div class="ag-ico amber"><i class="fa-solid fa-hourglass-half"></i></div><div><h3>{{ $stats['pending_count'] }}</h3><p>Pending</p></div></div>
        <div class="ag-card ag-stat"><div class="ag-ico amber"><i class="fa-solid fa-dollar-sign"></i></div><div><h3>${{ number_format($stats['pending_total'], 2) }}</h3><p>Pending Amount</p></div></div>
        <div class="ag-card ag-stat"><div class="ag-ico green"><i class="fa-solid fa-circle-check"></i></div><div><h3>${{ number_format($stats['approved_today'], 2) }}</h3><p>Approved Today</p></div></div>
        <div class="ag-card ag-stat"><div class="ag-ico blue"><i class="fa-solid fa-ban"></i></div><div><h3>{{ $stats['rejected_count'] }}</h3><p>Rejected</p></div></div>
    </div>

    <div class="ag-panel">
        <form method="GET" class="ag-filter" style="margin-bottom:18px;">
            <div class="ag-field"><label>Search</label><input type="text" name="search" value="{{ request('search') }}" placeholder="Agent, phone or Transaction ID"></div>
            <div class="ag-field">
                <label>Status</label>
                <select name="status">
                    <option value="">All</option>
                    @foreach(['pending','approved','rejected'] as $o)
                        <option value="{{ $o }}" @selected(request('status') === $o)>{{ ucfirst($o) }}</option>
                    @endforeach
                </select>
            </div>
            <button class="ag-btn">Filter</button>
        </form>

        @if($deposits->count())
            <div class="ag-table-wrap">
                <table class="ag-table">
                    <thead><tr><th>Date</th><th>Agent</th><th>Method / TrxID</th><th class="num">Amount</th><th>Status</th><th>Action</th></tr></thead>
                    <tbody>
                    @foreach($deposits as $d)
                        <tr>
                            <td>{{ $d->created_at->format('d M Y') }}<small>{{ $d->created_at->format('h:i A') }}</small></td>
                            <td>{{ $d->agent?->user?->name ?? 'Deleted' }}<small>{{ $d->agent?->user?->phone }}</small></td>
                            <td>{{ strtoupper($d->method) }}<small>{{ $d->transaction_id }}</small>@if($d->sender_info)<small>{{ $d->sender_info }}</small>@endif</td>
                            <td class="num">
                                ${{ number_format($d->amount, 2) }}
                                @if($d->status === 'approved')<small>Approved: ${{ number_format($d->approved_amount, 2) }}</small>@endif
                            </td>
                            <td>
                                <span class="ag-badge {{ $color[$d->status] ?? 'gray' }}">{{ ucfirst($d->status) }}</span>
                                @if($d->reviewer)<small>by {{ $d->reviewer->name }}</small>@endif
                                @if($d->admin_note)<small>{{ $d->admin_note }}</small>@endif
                            </td>
                            <td>
                                @if($d->status === 'pending')
                                    <details>
                                        <summary class="ag-btn ghost sm" style="display:inline-block;cursor:pointer;">Review</summary>

                                        <form method="POST" action="{{ route('super-admin.agent-deposits.approve', $d) }}" style="margin-top:10px;">
                                            @csrf @method('PATCH')
                                            <div class="ag-field"><label>Approve amount (USD)</label>
                                                <input type="number" name="approved_amount" step="0.01" min="0.01" max="{{ $d->amount }}" value="{{ $d->amount }}"></div>
                                            <div class="ag-field"><label>Note (optional)</label><input type="text" name="admin_note" maxlength="500"></div>
                                            <button class="ag-btn sm" onclick="return confirm('Approve this deposit?')">Approve</button>
                                        </form>

                                        <form method="POST" action="{{ route('super-admin.agent-deposits.reject', $d) }}" style="margin-top:10px;">
                                            @csrf @method('PATCH')
                                            <div class="ag-field"><label>Reject reason *</label><input type="text" name="admin_note" maxlength="500" required></div>
                                            <button class="ag-btn ghost sm" onclick="return confirm('Reject this deposit?')">Reject</button>
                                        </form>
                                    </details>
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div style="margin-top:16px;">{{ $deposits->links() }}</div>
        @else
            <div class="ag-empty"><i class="fa-solid fa-inbox"></i>No agent deposits found.</div>
        @endif
    </div>
</div>
@endsection