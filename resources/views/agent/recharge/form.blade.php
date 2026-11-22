@extends('layouts.app')
@section('title', 'Recharge Public User')
@section('content')

<div class="page-heading">
    <div><h2>Recharge Public User</h2><p>Search a user by ID, then recharge from your balance.</p></div>
    <a class="ag-btn ghost" href="{{ route('agent.recharge.history') }}"><i class="fa-solid fa-receipt"></i> Recharge History</a>
</div>

@include('partials.agent-styles')
@include('partials.agent-flash')

<div class="ag-wrap">

    @if(! $agent->isOperational())
        <div class="ag-notice err"><i class="fa-solid fa-ban"></i> Your agent account is not active. You cannot recharge users.</div>
    @endif

    <div class="ag-notice"><i class="fa-solid fa-wallet"></i> Your Recharge Balance: <strong>${{ number_format($agent->recharge_balance, 2) }}</strong></div>

    {{-- Search --}}
    <div class="ag-panel">
        <div class="ag-head"><div><h3>1. Find User</h3></div></div>
        <form method="GET" action="{{ route('agent.recharge.search') }}" class="ag-filter">
            <div class="ag-field">
                <label>User ID</label>
                <input type="number" name="user_id" min="1" value="{{ request('user_id') }}" placeholder="e.g. 10025" required>
            </div>
            <button class="ag-btn"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
        </form>
    </div>

    @if($searched && ! $found)
        <div class="ag-notice warn"><i class="fa-solid fa-user-slash"></i> No active public user found with this ID.</div>
    @endif

    {{-- Recharge --}}
    @if($found)
        <div class="ag-panel">
            <div class="ag-head"><div><h3>2. Recharge</h3></div></div>

            <div class="ag-grid ag-g3" style="margin-bottom:18px;">
                <div class="ag-card ag-stat"><div><h3>#{{ $found->id }}</h3><p>User ID</p></div></div>
                <div class="ag-card ag-stat"><div><h3>{{ $found->name }}</h3><p>Name</p></div></div>
                <div class="ag-card ag-stat"><div><h3>{{ rtrim(rtrim(number_format((float) $shares, 4, '.', ''), '0'), '.') ?: '0' }}</h3><p>Current Share</p></div></div>
            </div>

            @if($errors->any())
                <div class="ag-notice err"><i class="fa-solid fa-triangle-exclamation"></i> {{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('agent.recharge.store') }}"
                  onsubmit="return confirm('Recharge ' + this.amount.value + ' USD to {{ addslashes($found->name) }} (#{{ $found->id }})?');">
                @csrf
                <input type="hidden" name="user_id" value="{{ $found->id }}">
                <input type="hidden" name="token" value="{{ old('token', $token) }}">

                <div class="ag-field" style="max-width:320px;">
                    <label>Recharge Amount (USD) *</label>
                    <input type="number" name="amount" step="0.01" min="0.01" max="{{ $agent->recharge_balance }}" value="{{ old('amount') }}" placeholder="50" required>
                </div>

                <div style="margin-top:18px;">
                    <button class="ag-btn" @disabled(! $agent->isOperational() || (float) $agent->recharge_balance <= 0)>
                        <i class="fa-solid fa-bolt"></i> Confirm Recharge
                    </button>
                </div>
                <p style="font-size:12.5px;color:var(--muted);margin:12px 0 0;">The amount is deducted from your Recharge Balance and credited to the user's wallet (converted to the user's wallet currency).</p>
            </form>
        </div>
    @endif
</div>
@endsection