@extends('layouts.app')
@section('title', 'Buy Balance')
@section('content')

@php
    $cur = $agent->currency();
    $m = fn ($a) => \App\Support\AgentMoney::format($a, $cur);
    $disc = (float) $agent->discount_percent;
@endphp

<div class="page-heading">
    <div><h2>Buy Recharge Balance</h2><p>Send a deposit request to the company to add recharge balance.</p></div>
    <a class="ag-btn ghost" href="{{ route('agent.deposits.index') }}"><i class="fa-solid fa-clock-rotate-left"></i> Deposit History</a>
</div>

@include('partials.agent-styles')
@include('partials.agent-flash')

<div class="ag-wrap">
<div class="ag-notice"><i class="fa-solid fa-wallet"></i> Current Recharge Balance: <strong>{{ $m($agent->recharge_balance) }}</strong>@if($usd = \App\Support\AgentMoney::usd($agent->recharge_balance, $cur)) <small>({{ $usd }} · Converted View)</small>@endif. Balance is added only after the admin confirms your payment.</div>
    @if($disc > 0)
        <div class="ag-notice ok"><i class="fa-solid fa-tags"></i> You get <strong>{{ $disc + 0 }}% discount</strong>: you pay less than the balance you receive.</div>
    @endif

    <div class="ag-panel">
        @if($errors->any())
            <div class="ag-notice err"><i class="fa-solid fa-triangle-exclamation"></i> {{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('agent.deposits.store') }}">
            @csrf
            <div class="ag-grid ag-g2">
                <div class="ag-field">
                    <label>Balance you want to buy ({{ $cur }}) *</label>
                    <input type="number" id="buyAmount" name="amount" step="0.01" min="0.01" value="{{ old('amount') }}" required>
                </div>
                <div class="ag-field">
                    <label>You pay the company ({{ $cur }})</label>
                    <input type="text" id="payAmount" value="{{ $m(0) }}" readonly style="font-weight:700;">
                </div>
                <div class="ag-field">
                    <label>Payment Method *</label>
                    <select name="method" required>
                        <option value="">Select method</option>
                        @foreach($methods as $mt)
                            <option value="{{ $mt->code }}" @selected(old('method') === $mt->code)>{{ $mt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ag-field">
                    <label>Transaction ID *</label>
                    <input type="text" name="transaction_id" value="{{ old('transaction_id') }}" maxlength="100" required>
                </div>
                <div class="ag-field">
                    <label>Sender account / wallet (optional)</label>
                    <input type="text" name="sender_info" value="{{ old('sender_info') }}" maxlength="150">
                </div>
            </div>
            <p style="font-size:12.5px;color:var(--muted);margin:14px 0 0;">Pay the amount shown in "You pay the company" using the selected method, then enter the Transaction ID.</p>
            <div style="margin-top:18px;"><button class="ag-btn"><i class="fa-solid fa-paper-plane"></i> Submit Request</button></div>
        </form>
    </div>
</div>

<script>
    (function () {
        const disc = {{ $disc }};
        const symbol = @json(trim(preg_replace('/[0-9.,\s]/', '', $m(0))));
        const buy = document.getElementById('buyAmount');
        const pay = document.getElementById('payAmount');

        function update() {
            const v = parseFloat(buy.value) || 0;
            const p = v - Math.floor(v * disc) / 100 * 0 - (Math.floor(v * disc * 100 / 100) / 100);
            const payable = v - Math.floor(v * disc) / 100;
            pay.value = symbol + ' ' + payable.toFixed(2);
        }

        buy.addEventListener('input', update);
        update();
    })();
</script>
@endsection