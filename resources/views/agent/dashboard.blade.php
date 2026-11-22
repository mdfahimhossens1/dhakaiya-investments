@extends('layouts.app')
@section('title', 'Agent Dashboard')
@section('content')

@php
    use Illuminate\Support\Facades\Route;

    // Route না থাকলে বাটন দেখাবে না, তাই পেজ ভাঙবে না
    $menu = collect([
        ['agent.deposits.create',   'fa-solid fa-circle-plus',          'Deposit',            'Add balance from company'],
        ['agent.deposits.index',    'fa-solid fa-clock-rotate-left',    'Deposit History',    'Your deposit requests'],
        ['agent.recharge.search',   'fa-solid fa-magnifying-glass',     'Public User Search', 'Find a user by ID'],
        ['agent.recharge.create',   'fa-solid fa-bolt',                 'Recharge',           'Recharge a user'],
        ['agent.recharge.history',  'fa-solid fa-receipt',              'Recharge History',   'Recharges you made'],
        ['agent.commission.index',  'fa-solid fa-percent',              'Commission',         'Your commission'],
        ['agent.transactions.index','fa-solid fa-list-ul',              'Transactions',       'Full ledger'],
    ])->filter(fn ($m) => Route::has($m[0]));

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
        <div class="ag-notice warn"><i class="fa-solid fa-wallet"></i> Your recharge balance is <strong>$0.00</strong>. Send a deposit request to the company first, then you can recharge users.</div>
    @endif

    {{-- Money --}}
    <div class="ag-grid ag-g4">
        <div class="ag-card ag-stat"><div class="ag-ico green"><i class="fa-solid fa-wallet"></i></div><div><h3>${{ number_format($money['balance'], 2) }}</h3><p>Recharge Balance</p></div></div>
        <div class="ag-card ag-stat"><div class="ag-ico blue"><i class="fa-solid fa-building-columns"></i></div><div><h3>${{ number_format($money['total_deposited'], 2) }}</h3><p>Total Deposited</p></div></div>
        <div class="ag-card ag-stat"><div class="ag-ico purple"><i class="fa-solid fa-bolt"></i></div><div><h3>${{ number_format($money['total_recharged'], 2) }}</h3><p>Total Recharged ({{ $money['recharge_count'] }})</p></div></div>
        <div class="ag-card ag-stat"><div class="ag-ico amber"><i class="fa-solid fa-hourglass-half"></i></div><div><h3>${{ number_format($money['pending_deposit'], 2) }}</h3><p>Pending Deposit ({{ $money['pending_count'] }})</p></div></div>
    </div>

    <div class="ag-grid ag-g2">
        <div class="ag-card ag-stat"><div class="ag-ico teal"><i class="fa-solid fa-calendar-day"></i></div><div><h3>${{ number_format($money['today_recharged'], 2) }}</h3><p>Recharged Today</p></div></div>
        <div class="ag-card ag-stat"><div class="ag-ico amber"><i class="fa-solid fa-percent"></i></div><div><h3>${{ number_format($money['commission'], 2) }}</h3><p>Commission Earned</p></div></div>
    </div>

    <div class="ag-notice"><i class="fa-solid fa-circle-info"></i> Your <strong>Recharge Balance</strong> is separate from a user's Investment Wallet. A recharge deducts from your balance and credits the user's wallet.</div>

    {{-- Quick menu --}}
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
        {{-- Recent recharges --}}
        <div class="ag-panel">
            <div class="ag-head"><div><h3>Recent Recharges</h3></div>
                @if(Route::has('agent.recharge.history'))<a class="ag-btn ghost sm" href="{{ route('agent.recharge.history') }}">View all</a>@endif
            </div>
            @if($recentRecharges->count())
                <div class="ag-table-wrap">
                    <table class="ag-table">
                        <thead><tr><th>User</th><th class="num">Amount</th><th>Status</th></tr></thead>
                        <tbody>
                        @foreach($recentRecharges as $r)
                            <tr>
                                <td>{{ $r->user?->name ?? 'User' }}<small>#{{ $r->user_id }} · {{ $r->created_at->diffForHumans() }}</small></td>
                                <td class="num">${{ number_format($r->amount, 2) }}</td>
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

        {{-- Recent deposits --}}
        <div class="ag-panel">
            <div class="ag-head"><div><h3>Recent Deposits</h3></div>
                @if(Route::has('agent.deposits.index'))<a class="ag-btn ghost sm" href="{{ route('agent.deposits.index') }}">View all</a>@endif
            </div>
            @if($recentDeposits->count())
                <div class="ag-table-wrap">
                    <table class="ag-table">
                        <thead><tr><th>Method</th><th class="num">Amount</th><th>Status</th></tr></thead>
                        <tbody>
                        @foreach($recentDeposits as $d)
                            <tr>
                                <td>{{ strtoupper($d->method) }}<small>{{ $d->transaction_id }} · {{ $d->created_at->diffForHumans() }}</small></td>
                                <td class="num">${{ number_format($d->amount, 2) }}</td>
                                <td><span class="ag-badge {{ $statusColor[$d->status] ?? 'gray' }}">{{ ucfirst($d->status) }}</span></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="ag-empty"><i class="fa-solid fa-inbox"></i>No deposit requests yet.</div>
            @endif
        </div>
    </div>

    {{-- Research + Areas (আগের ফিচার) --}}
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
            <p style="font-size:12.5px;color:var(--muted);margin:0 0 6px;">
                Research: {{ $counts['pending'] ?? 0 }} pending · {{ ($counts['accepted'] ?? 0) + ($counts['in_progress'] ?? 0) }} in progress · {{ $counts['completed'] ?? 0 }} completed
                @if(Route::has('agent.messages.index')) · <a href="{{ route('agent.messages.index') }}">{{ $unread }} unread</a>@endif
            </p>
            <p style="font-size:12.5px;color:var(--muted);margin:0;">Add your payment methods in <a href="{{ route('agent.profile.index') }}">My Profile</a>.</p>
        </div>
    </div>
</div>
</div>
@endsection