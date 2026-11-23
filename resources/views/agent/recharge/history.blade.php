@extends('layouts.app')
@section('title', 'Recharge History')
@section('content')

@php
    $cur = $agent->currency();
    $m = fn ($a, $c = null) => \App\Support\AgentMoney::format($a, $c ?: $cur);
    $color = ['completed' => 'green', 'reversed' => 'gray'];
@endphp

<div class="page-heading">
    <div><h2>Recharge History</h2><p>Every recharge you made to users.</p></div>
    <a class="ag-btn" href="{{ route('agent.recharge.create') }}"><i class="fa-solid fa-bolt"></i> New Recharge</a>
</div>

@include('partials.agent-styles')
@include('partials.agent-flash')

<div class="ag-wrap">
    <div class="ag-grid ag-g2">
        <div class="ag-card ag-stat"><div class="ag-ico purple"><i class="fa-solid fa-bolt"></i></div><div><h3>{{ $summary['count'] }}</h3><p>Recharges</p></div></div>
        <div class="ag-card ag-stat"><div class="ag-ico green"><i class="fa-solid fa-coins"></i></div><div><h3>{{ $m($summary['total']) }}</h3><p>Total Recharged</p></div></div>
    </div>

    <div class="ag-panel">
        <form method="GET" class="ag-filter" style="margin-bottom:18px;">
            <div class="ag-field"><label>User ID</label><input type="number" name="user_id" value="{{ request('user_id') }}"></div>
            <div class="ag-field"><label>From</label><input type="date" name="from" value="{{ request('from') }}"></div>
            <div class="ag-field"><label>To</label><input type="date" name="to" value="{{ request('to') }}"></div>
            <button class="ag-btn">Filter</button>
        </form>

        @if($recharges->count())
            <div class="ag-table-wrap">
                <table class="ag-table">
                    <thead><tr><th>Date</th><th>User</th><th class="num">Amount</th><th>Status</th></tr></thead>
                    <tbody>
                    @foreach($recharges as $r)
                        <tr>
                            <td>{{ $r->created_at->format('d M Y') }}<small>{{ $r->created_at->format('h:i A') }}</small></td>
                            <td>{{ $r->user?->name ?? 'User' }}<small>#{{ $r->user_id }}</small></td>
                            <td class="num">{{ $m($r->wallet_amount, $r->wallet_currency) }}</td>
                            <td><span class="ag-badge {{ $color[$r->status] ?? 'gray' }}">{{ ucfirst($r->status) }}</span></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div style="margin-top:16px;">{{ $recharges->links() }}</div>
        @else
            <div class="ag-empty"><i class="fa-solid fa-bolt"></i>No recharges found.</div>
        @endif
    </div>
</div>
@endsection