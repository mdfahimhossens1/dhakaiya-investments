@extends('layouts.app')
@section('title', 'Deposit History')
@section('content')

@php
    $cur = $agent->currency();
    $m = fn ($a, $c = null) => \App\Support\AgentMoney::format($a, $c ?: $cur);
    $color = ['pending' => 'amber', 'approved' => 'green', 'rejected' => 'red'];
@endphp

<div class="page-heading">
    <div><h2>Deposit History</h2><p>Your balance purchase requests to the company.</p></div>
    <a class="ag-btn" href="{{ route('agent.deposits.create') }}"><i class="fa-solid fa-plus"></i> Buy Balance</a>
</div>

@include('partials.agent-styles')
@include('partials.agent-flash')

<div class="ag-wrap">
    <div class="ag-panel">
        <form method="GET" class="ag-filter" style="margin-bottom:18px;">
            <div class="ag-field">
                <label>Status</label>
                <select name="status">
                    <option value="">All</option>
                    @foreach(['pending','approved','rejected'] as $o)
                        <option value="{{ $o }}" @selected($status === $o)>{{ ucfirst($o) }}</option>
                    @endforeach
                </select>
            </div>
            <button class="ag-btn">Filter</button>
        </form>

        @if($deposits->count())
            <div class="ag-table-wrap">
                <table class="ag-table">
                    <thead><tr><th>Date</th><th>Method</th><th>Transaction ID</th><th class="num">Balance</th><th class="num">Discount</th><th class="num">You Pay</th><th>Status</th><th>Note</th></tr></thead>
                    <tbody>
                    @foreach($deposits as $d)
                        <tr>
                            <td>{{ $d->created_at->format('d M Y') }}<small>{{ $d->created_at->format('h:i A') }}</small></td>
                            <td>{{ strtoupper($d->method) }}</td>
                            <td>{{ $d->transaction_id }}</td>
                            <td class="num">
                                {{ $m($d->status === 'approved' ? $d->approved_amount : $d->amount, $d->currency) }}
                            </td>
                            <td class="num">{{ (float) $d->discount_percent + 0 }}%</td>
                            <td class="num">{{ $m($d->payable_amount ?? $d->amount, $d->currency) }}</td>
                            <td><span class="ag-badge {{ $color[$d->status] ?? 'gray' }}">{{ ucfirst($d->status) }}</span></td>
                            <td>{{ $d->admin_note ?: '—' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div style="margin-top:16px;">{{ $deposits->links() }}</div>
        @else
            <div class="ag-empty"><i class="fa-solid fa-inbox"></i>No deposit requests yet.</div>
        @endif
    </div>
</div>
@endsection