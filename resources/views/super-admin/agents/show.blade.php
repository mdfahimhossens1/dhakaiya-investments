@extends('layouts.app')
@section('title', 'Agent Details')
@section('content')

@php
    $cur = $money['currency'];
    $m = fn ($a) => \App\Support\AgentMoney::format($a, $cur);
    $usd = \App\Support\AgentMoney::usd($money['balance'], $cur);
    $badge = ['approved' => 'green', 'pending' => 'amber', 'rejected' => 'red'];
    $labels = ['deposit' => 'Balance Purchase', 'recharge' => 'Recharge', 'commission' => 'Commission', 'adjustment' => 'Adjustment'];
@endphp

<div class="page-heading">
    <div><h2>{{ $agent->user->name ?? 'Agent' }}</h2><p>Recharge agent details and balance.</p></div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
        <a class="ag-btn ghost" href="{{ route('super-admin.agents.index') }}"><i class="fa-solid fa-arrow-left"></i> Back</a>
        <a class="ag-btn" href="{{ route('super-admin.agents.edit', $agent) }}"><i class="fa-solid fa-pen"></i> Edit</a>
    </div>
</div>

@include('partials.agent-styles')
@include('partials.agent-flash')

<div class="ag-wrap">

    <div class="ag-grid ag-g4">
        <div class="ag-card ag-stat"><div class="ag-ico green"><i class="fa-solid fa-wallet"></i></div><div>
            <h3>{{ $m($money['balance']) }}</h3><p>Recharge Balance</p>
            @if($usd)<small>{{ $usd }} · Converted View</small>@endif
        </div></div>
        <div class="ag-card ag-stat"><div class="ag-ico blue"><i class="fa-solid fa-building-columns"></i></div><div><h3>{{ $m($money['total_deposited']) }}</h3><p>Total Balance Bought</p></div></div>
        <div class="ag-card ag-stat"><div class="ag-ico purple"><i class="fa-solid fa-bolt"></i></div><div><h3>{{ $m($money['total_recharged']) }}</h3><p>Total Recharged ({{ $money['recharge_count'] }})</p></div></div>
        <div class="ag-card ag-stat"><div class="ag-ico amber"><i class="fa-solid fa-tags"></i></div><div><h3>{{ (float) $money['discount'] + 0 }}%</h3><p>Discount on balance purchase</p></div></div>
    </div>

    <div class="ag-grid ag-g2">
        <div class="ag-panel">
            <div class="ag-head"><div><h3>Profile</h3></div></div>
            <table class="ag-table">
                <tbody>
                    <tr><td>Email</td><td>{{ $agent->user->email ?? '—' }}</td></tr>
                    <tr><td>Phone</td><td>{{ $agent->phone ?: ($agent->user->phone ?? '—') }}</td></tr>
                    <tr><td>WhatsApp</td><td>{{ $agent->whatsapp ?: '—' }}</td></tr>
                    <tr><td>Areas</td><td>{{ $agent->districts->pluck('name')->implode(', ') ?: '—' }}</td></tr>
                    <tr><td>Approval</td><td><span class="ag-badge {{ $badge[$agent->approval_status] ?? 'gray' }}">{{ ucfirst($agent->approval_status) }}</span></td></tr>
                    <tr><td>Verified</td><td>@if($agent->is_verified)<span class="ag-badge blue">Verified</span>@else<span class="ag-badge gray">Not verified</span>@endif</td></tr>
                    <tr><td>Status</td><td>@if($agent->is_active)<span class="ag-badge green">Active</span>@else<span class="ag-badge red">Inactive</span>@endif
                        <span class="ag-badge {{ $agent->is_available ? 'teal' : 'gray' }}">{{ $agent->is_available ? 'Available' : 'Unavailable' }}</span></td></tr>
                </tbody>
            </table>

            <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:14px;">
                <form method="POST" action="{{ route('super-admin.agents.verify', $agent) }}">@csrf @method('PATCH')
                    <button class="ag-btn ghost sm">{{ $agent->is_verified ? 'Unverify' : 'Verify' }}</button></form>
                @if($agent->approval_status !== 'approved')
                    <form method="POST" action="{{ route('super-admin.agents.approval', $agent) }}">@csrf @method('PATCH')
                        <input type="hidden" name="approval_status" value="approved">
                        <button class="ag-btn ghost sm">Approve</button></form>
                @endif
                <form method="POST" action="{{ route('super-admin.agents.status', $agent) }}">@csrf @method('PATCH')
                    <button class="ag-btn ghost sm">{{ $agent->is_active ? 'Deactivate' : 'Activate' }}</button></form>
            </div>
        </div>

        <div class="ag-panel">
            <div class="ag-head"><div><h3>Payment Methods</h3><p>Where users pay this agent.</p></div></div>
            @forelse($agent->paymentMethods as $pm)
                @php
                    $label = \App\Models\AgentPaymentMethod::METHODS[$pm->method] ?? $pm->method;
                    if (is_array($label)) { $label = $label['label'] ?? $pm->method; }
                @endphp
                <p style="margin:0 0 10px;font-size:13px;">
                    <strong>{{ $label }}</strong>
                    <span class="ag-badge {{ $pm->is_active ? 'green' : 'gray' }}">{{ $pm->is_active ? 'Active' : 'Off' }}</span><br>
                    {{ $pm->account_name }} · {{ $pm->account_number }}@if($pm->bank_name) · {{ $pm->bank_name }}@endif
                </p>
            @empty
                <div class="ag-empty" style="padding:20px;">No payment methods added.</div>
            @endforelse
        </div>
    </div>

    <div class="ag-panel">
        <div class="ag-head"><div><h3>Recent Transactions</h3><p>Latest 10 recharge-balance entries.</p></div></div>
        @if($recentTransactions->count())
            <div class="ag-table-wrap">
                <table class="ag-table">
                    <thead><tr><th>Date</th><th>Type</th><th>Description</th><th class="num">Amount</th><th class="num">Balance after</th></tr></thead>
                    <tbody>
                    @foreach($recentTransactions as $t)
                        <tr>
                            <td>{{ $t->created_at->format('d M Y') }}<small>{{ $t->created_at->format('h:i A') }}</small></td>
                            <td><span class="ag-badge {{ $t->direction === 'credit' ? 'green' : 'amber' }}">{{ $labels[$t->type] ?? ucfirst($t->type) }}</span></td>
                            <td>{{ $t->description ?: '—' }}</td>
                            <td class="num">{{ $t->direction === 'credit' ? '+' : '−' }}{{ $m($t->amount) }}</td>
                            <td class="num">{{ $m($t->balance_after) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="ag-empty"><i class="fa-solid fa-list-ul"></i>No transactions yet.</div>
        @endif
    </div>
</div>
@endsection