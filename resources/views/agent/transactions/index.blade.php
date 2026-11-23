@extends('layouts.app')
@section('title', 'Transactions')
@section('content')

@php
    $cur = $agent->currency();
    $m = fn ($a) => \App\Support\AgentMoney::format($a, $cur);
    $labels = ['deposit' => 'Balance Purchase', 'recharge' => 'Recharge', 'commission' => 'Commission', 'adjustment' => 'Adjustment'];
@endphp

<div class="page-heading">
    <div><h2>Transactions</h2><p>Full ledger of your recharge balance.</p></div>
</div>

@include('partials.agent-styles')
@include('partials.agent-flash')

<div class="ag-wrap">
<div class="ag-notice"><i class="fa-solid fa-wallet"></i> Current Recharge Balance: <strong>{{ $m($agent->recharge_balance) }}</strong>@if($usd = \App\Support\AgentMoney::usd($agent->recharge_balance, $cur)) <small>({{ $usd }} · Converted View)</small>@endif</div>
    <div class="ag-panel">
        <form method="GET" class="ag-filter" style="margin-bottom:18px;">
            <div class="ag-field">
                <label>Type</label>
                <select name="type">
                    <option value="all">All</option>
                    @foreach($types as $t)
                        <option value="{{ $t }}" @selected($type === $t)>{{ $labels[$t] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="ag-field"><label>From</label><input type="date" name="from" value="{{ request('from') }}"></div>
            <div class="ag-field"><label>To</label><input type="date" name="to" value="{{ request('to') }}"></div>
            <button class="ag-btn">Filter</button>
        </form>

        @if($transactions->count())
            <div class="ag-table-wrap">
                <table class="ag-table">
                    <thead><tr><th>Date</th><th>Type</th><th>Description</th><th class="num">Amount</th><th class="num">Balance after</th></tr></thead>
                    <tbody>
                    @foreach($transactions as $t)
                        <tr>
                            <td>{{ $t->created_at->format('d M Y') }}<small>{{ $t->created_at->format('h:i A') }}</small></td>
                            <td><span class="ag-badge {{ $t->direction === 'credit' ? 'green' : 'amber' }}">{{ $labels[$t->type] ?? ucfirst($t->type) }}</span></td>
                            <td>{{ $t->description ?: '—' }}</td>
                            <td class="num" style="color:{{ $t->direction === 'credit' ? '#087f5b' : '#b45309' }};font-weight:700;">
                                {{ $t->direction === 'credit' ? '+' : '−' }}{{ $m($t->amount) }}
                            </td>
                            <td class="num">{{ $m($t->balance_after) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div style="margin-top:16px;">{{ $transactions->links() }}</div>
        @else
            <div class="ag-empty"><i class="fa-solid fa-list-ul"></i>No transactions yet.</div>
        @endif
    </div>
</div>
@endsection