@extends('layouts.app')
@section('title', 'Agent Dashboard')
@section('content')

@php
    $cur = $agent->currency();
    $m = fn ($a) => \App\Support\AgentMoney::format($a, $cur);
    $has = fn ($r) => \Illuminate\Support\Facades\Route::has($r);

    $menu = collect([
        ['agent.deposits.create',    'fa-solid fa-circle-plus',       'Buy Balance',        'Add balance from company'],
        ['agent.deposits.index',     'fa-solid fa-clock-rotate-left', 'Deposit History',    'Your deposit requests'],
        ['agent.recharge.search',    'fa-solid fa-magnifying-glass',  'Public User Search', 'Find a user by ID'],
        ['agent.recharge.create',    'fa-solid fa-bolt',              'Recharge',           'Recharge a user'],
        ['agent.recharge.history',   'fa-solid fa-receipt',           'Recharge History',   'Recharges you made'],
        ['agent.transactions.index', 'fa-solid fa-list-ul',           'Transactions',       'Full ledger'],
    ])->filter(fn ($i) => $has($i[0]));

    $statusColor = ['pending' => 'amber', 'approved' => 'green', 'rejected' => 'red', 'completed' => 'green', 'reversed' => 'gray'];
@endphp

<div class="agent-page">
<div class="page-heading">
    <div><h2>Welcome, {{ auth()->user()->name }}</h2><p>Your recharge balance and activity at a glance.</p></div>
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
        <div class="ag-notice err"><i class="fa-solid fa-ban"></i> Your agent account is currently <strong>{{ $agent->approval_status !== 'approved' ? $agent->approval_status : 'inactive' }}</strong>. You cannot recharge users until the admin enables it.</div>
    @elseif(! $agent->is_verified)
        <div class="ag-notice warn"><i class="fa-solid fa-badge-check"></i> Your account is not verified yet. Users cannot find you until the admin verifies you.</div>
    @endif

    @if($agent->isOperational() && (float) $money['balance'] <= 0)
        <div class="ag-notice warn"><i class="fa-solid fa-wallet"></i> Your recharge balance is <strong>{{ $m(0) }}</strong>. Buy balance from the company first, then you can recharge users.</div>
    @endif

    @if((float) $agent->discount_percent > 0)
        <div class="ag-notice ok"><i class="fa-solid fa-tags"></i> You get <strong>{{ (float) $agent->discount_percent + 0 }}% discount</strong> when you buy balance from the company.</div>
    @endif

    <div class="ag-grid ag-g4">
        <div class="ag-card ag-stat"><div class="ag-ico green"><i class="fa-solid fa-wallet"></i></div><div><h3>{{ $m($money['balance']) }}</h3><p>Recharge Balance</p>@if($usd = \App\Support\AgentMoney::usd($money['balance'], $cur))<small>{{ $usd }} · Converted View</small>@endif</div></div>
        <div class="ag-card ag-stat"><div class="ag-ico blue"><i class="fa-solid fa-building-columns"></i></div><div><h3>{{ $m($money['total_deposited']) }}</h3><p>Total Balance Bought</p></div></div>
        <div class="ag-card ag-stat"><div class="ag-ico purple"><i class="fa-solid fa-bolt"></i></div><div><h3>{{ $m($money['total_recharged']) }}</h3><p>Total Recharged ({{ $money['recharge_count'] }})</p></div></div>
        <div class="ag-card ag-stat"><div class="ag-ico amber"><i class="fa-solid fa-hourglass-half"></i></div><div><h3>{{ $m($money['pending_deposit']) }}</h3><p>Pending Request ({{ $money['pending_count'] }})</p></div></div>
    </div>

    <div class="ag-grid ag-g2">
        <div class="ag-card ag-stat"><div class="ag-ico teal"><i class="fa-solid fa-calendar-day"></i></div><div><h3>{{ $m($money['today_recharged']) }}</h3><p>Recharged Today</p></div></div>
        <div class="ag-card ag-stat"><div class="ag-ico amber"><i class="fa-solid fa-percent"></i></div><div><h3>{{ (float) $agent->discount_percent + 0 }}%</h3><p>Your Discount</p></div></div>
    </div>

    <div class="ag-notice"><i class="fa-solid fa-circle-info"></i> Your <strong>Recharge Balance</strong> is separate from a user's Investment Wallet. A recharge deducts from your balance and credits the user's wallet. You can recharge users whose wallet currency is {{ $cur }}.</div>

    @if($menu->count())
        <div class="ag-panel">
            <div class="ag-head"><div><h3>Quick Actions</h3></div></div>
            <div style="display:flex;gap:10px;flex-wrap:wrap;">
                @foreach($menu as [$route, $icon, $label, $hint])
                    <a class="ag-btn ghost" href="{{ route($route) }}" title="{{ $hint }}"><i class="{{ $icon }}"></i> {{ $label }}</a>
                @endforeach
            </div>
        </div>
    @endif

    <div class="ag-grid ag-g2">
        <div class="ag-panel">
            <div class="ag-head"><div><h3>Recent Recharges</h3></div>
                @if($has('agent.recharge.history'))<a class="ag-btn ghost sm" href="{{ route('agent.recharge.history') }}">View all</a>@endif
            </div>
            @if($recentRecharges->count())
                <div class="ag-table-wrap">
                    <table class="ag-table">
                        <thead><tr><th>User</th><th class="num">Amount</th><th>Status</th></tr></thead>
                        <tbody>
                        @foreach($recentRecharges as $r)
                            <tr>
                                <td>{{ $r->user?->name ?? 'User' }}<small>#{{ $r->user_id }} · {{ $r->created_at->diffForHumans() }}</small></td>
                                <td class="num">{{ $m($r->amount) }}</td>
                                <td><span class="ag-badge {{ $statusColor[$r->status] ?? 'gray' }}">{{ ucfirst($r->status) }}</span></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="ag-empty"><i class="fa-solid fa-bolt"></i>No recharges yet.</div>
            @endif
        </div>

        <div class="ag-panel">
            <div class="ag-head"><div><h3>Recent Balance Requests</h3></div>
                @if($has('agent.deposits.index'))<a class="ag-btn ghost sm" href="{{ route('agent.deposits.index') }}">View all</a>@endif
            </div>
            @if($recentDeposits->count())
                <div class="ag-table-wrap">
                    <table class="ag-table">
                        <thead><tr><th>Method</th><th class="num">Balance</th><th>Status</th></tr></thead>
                        <tbody>
                        @foreach($recentDeposits as $d)
                            <tr>
                                <td>{{ strtoupper($d->method) }}<small>{{ $d->transaction_id }} · {{ $d->created_at->diffForHumans() }}</small></td>
                                <td class="num">{{ $m($d->amount) }}</td>
                                <td><span class="ag-badge {{ $statusColor[$d->status] ?? 'gray' }}">{{ ucfirst($d->status) }}</span></td>
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
            <p style="margin:0;">
                <span class="ag-badge {{ $agent->is_verified ? 'blue' : 'gray' }}">{{ $agent->is_verified ? 'Verified' : 'Not verified' }}</span>
                <span class="ag-badge {{ $agent->is_available ? 'teal' : 'gray' }}">{{ $agent->is_available ? 'Available' : 'Unavailable' }}</span>
            </p>
        </div>
    </div>
</div>
</div>
@endsection