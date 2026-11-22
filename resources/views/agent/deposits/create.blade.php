@extends('layouts.app')
@section('title', 'Deposit')
@section('content')

<div class="page-heading">
    <div><h2>Deposit</h2><p>Send a deposit request to the company to add recharge balance.</p></div>
    <a class="ag-btn ghost" href="{{ route('agent.deposits.index') }}"><i class="fa-solid fa-clock-rotate-left"></i> Deposit History</a>
</div>

@include('partials.agent-styles')
@include('partials.agent-flash')

<div class="ag-wrap">
    <div class="ag-notice"><i class="fa-solid fa-wallet"></i> Current Recharge Balance: <strong>${{ number_format($agent->recharge_balance, 2) }}</strong>. Pay the company first, then submit the payment details below. Balance is added only after the admin approves.</div>

    <div class="ag-panel">
        @if($errors->any())
            <div class="ag-notice err"><i class="fa-solid fa-triangle-exclamation"></i> {{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('agent.deposits.store') }}">
            @csrf
            <div class="ag-grid ag-g2">
                <div class="ag-field">
                    <label>Deposit Amount (USD) *</label>
                    <input type="number" name="amount" step="0.01" min="0.01" value="{{ old('amount') }}" placeholder="500" required>
                </div>
                <div class="ag-field">
                    <label>Payment Method *</label>
                    <select name="method" required>
                        <option value="">Select method</option>
                        @foreach($methods as $m)
                            <option value="{{ $m->code }}" @selected(old('method') === $m->code)>{{ $m->name }}</option>
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
            <div style="margin-top:18px;"><button class="ag-btn"><i class="fa-solid fa-paper-plane"></i> Submit Deposit Request</button></div>
        </form>
    </div>
</div>
@endsection