@extends('layouts.app')

@section('title', 'Withdraw')

@section('content')

@include('user.wallet._styles')

@php
    $sym = \App\Support\Currency::symbol($currency);
    $recent = $user->withdrawRequests()->where('currency', $currency)->latest()->limit(5)->get();
@endphp

<div class="wl-page">

    <div class="wl-hero">
        <div>
            <h2>💸 Withdraw</h2>
            <p>Withdraw your earnings to one of your saved payment accounts.</p>
        </div>
        <div class="wl-hero-actions">
            <a href="{{ route('user.withdrawal-history.index') }}">History</a>
            <a href="{{ route('user.payment-information.index') }}" class="alt">Payment Accounts</a>
        </div>
    </div>

    @include('user.wallet._flash')

    @if(count($currencies) > 1)
        <div class="wl-tabs">
            @foreach($currencies as $c)
                <a href="{{ route('user.withdraw', ['currency' => $c]) }}" class="{{ $c === $currency ? 'on' : '' }}">{{ $c }} Wallet</a>
            @endforeach
        </div>
    @endif

    <div class="wl-stats">
        <div class="wl-stat"><div class="ic"><i class="fas fa-wallet"></i></div>
            <div><p>Withdrawable (Earnings)</p><h3>{{ $sym }} {{ number_format((float) $withdrawalSummary['available_balance'], 2) }}</h3><small>{{ $currency }} wallet</small></div></div>
        <div class="wl-stat"><div class="ic blue"><i class="fas fa-arrow-down-wide-short"></i></div>
            <div><p>Minimum Withdrawal</p><h3>{{ $sym }} {{ number_format((float) $withdrawalSummary['minimum_withdrawal'], 2) }}</h3><small>Per request</small></div></div>
        <div class="wl-stat"><div class="ic orange"><i class="fas fa-clock"></i></div>
            <div><p>Pending (on hold)</p><h3>{{ $sym }} {{ number_format((float) $withdrawalSummary['pending_withdrawals'], 2) }}</h3><small>Awaiting payout</small></div></div>
        <div class="wl-stat"><div class="ic purple"><i class="fas fa-circle-check"></i></div>
            <div><p>Total Paid Out</p><h3>{{ $sym }} {{ number_format((float) $withdrawalSummary['completed_withdrawals'], 2) }}</h3><small>Completed</small></div></div>
    </div>

    <div class="wl-notice" style="margin-bottom:16px"><i class="fas fa-lock"></i>
        <div>
            <h4>Locked balance: {{ $sym }} {{ number_format((float) $withdrawalSummary['principal_balance'], 2) }}</h4>
            <p>Deposit and share resale money can be used to buy shares but cannot be withdrawn. Only profit, commission and rewards can be withdrawn.</p>
        </div>
    </div>

    @if($nextWithdrawAt)
        <div class="wl-flash err">
            ⏳ You can withdraw once every {{ $intervalDays }} days.
            Next withdrawal available: <strong>{{ $nextWithdrawAt->format('d M Y, h:i A') }}</strong>
        </div>
    @elseif($intervalDays)
        <div class="wl-notice" style="margin-bottom:16px"><i class="fas fa-info-circle"></i>
            <div><p>Withdrawals are allowed once every {{ $intervalDays }} days.</p></div></div>
    @endif

    <div class="wl-card">
        <div class="wl-card-h">
            <div><h3>Request Withdrawal</h3><p>Minimum {{ $sym }} {{ number_format((float) $withdrawalSummary['minimum_withdrawal'], 2) }} per request.</p></div>
        </div>

        @if($withdrawalMethods->isEmpty())
            <div class="wl-empty">
                <div class="ic"><i class="fas fa-building-columns"></i></div>
                <h4>No {{ $currency }} payment account found</h4>
                <p>Add an account first. <a href="{{ route('user.payment-information.index') }}">Add payment account</a></p>
            </div>
        @else
            <form method="POST" action="{{ route('user.withdraw.store') }}" class="wl-form" style="max-width:520px">
                @csrf
                <input type="hidden" name="currency" value="{{ $currency }}">

                <label for="acc">Send to</label>
                <select name="payment_account_id" id="acc" required onchange="wdCalc()">
                    @foreach($withdrawalMethods as $a)
                        <option value="{{ $a->id }}" data-fee="{{ $a->paymentMethod->withdraw_fee_percent }}"
                                @selected((string) old('payment_account_id') === (string) $a->id)>
                            {{ $a->paymentMethod->name }} — {{ $a->maskedDetails() }}{{ $a->is_default ? ' (Default)' : '' }}
                        </option>
                    @endforeach
                </select>

                <label for="amt">Amount ({{ $currency }}) — max {{ $sym }} {{ number_format((float) $withdrawalSummary['available_balance'], 2) }}</label>
                <input type="number" step="0.01" min="{{ $withdrawalSummary['minimum_withdrawal'] }}"
                       max="{{ $withdrawalSummary['available_balance'] }}"
                       name="amount" id="amt" value="{{ old('amount') }}" required oninput="wdCalc()">

                <div class="wl-preview" id="wdPreview"></div>

                <button type="submit" class="wl-btn primary" @disabled($nextWithdrawAt)>Submit Withdrawal Request</button>
            </form>
        @endif
    </div>

    <div class="wl-card">
        <div class="wl-card-h">
            <div><h3>Recent Withdrawal Activity</h3><p>Your latest {{ $currency }} withdrawal requests.</p></div>
            <a href="{{ route('user.withdrawal-history.index') }}" class="wl-pill">View All</a>
        </div>

        @if($recent->isEmpty())
            <div class="wl-empty">
                <div class="ic"><i class="fas fa-receipt"></i></div>
                <h4>No Withdrawal Activity</h4>
                <p>Your withdrawal requests will appear here.</p>
            </div>
        @else
            <div class="wl-table-wrap">
                <table class="wl-table" style="min-width:560px">
                    <thead><tr><th>ID</th><th>Date</th><th>Amount</th><th>Receive</th><th>Status</th></tr></thead>
                    <tbody>
                        @foreach($recent as $w)
                            <tr>
                                <td>#WR-{{ $w->id }}</td>
                                <td>{{ $w->created_at->format('d M Y') }}</td>
                                <td>{{ $sym }} {{ number_format((float) $w->amount, 2) }}</td>
                                <td>{{ $sym }} {{ number_format((float) $w->net_amount, 2) }}</td>
                                <td><span class="wl-badge {{ $w->status }}">{{ $w->status === 'approved' ? 'Paid' : ucfirst($w->status) }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="wl-notice">
        <i class="fas fa-circle-info"></i>
        <div>
            <h4>How withdrawal works</h4>
            <p>The requested amount is held from your earnings right away. Our team sends the money to your account and marks the request <strong>Paid</strong>. If it is rejected, the amount returns to your earnings. Fees depend on the payment method.</p>
        </div>
    </div>

</div>

<script>
function wdCalc() {
    var sel = document.getElementById('acc'), amt = document.getElementById('amt'), box = document.getElementById('wdPreview');
    if (!sel || !amt || !box) return;
    var v = parseFloat(amt.value);
    if (!v || v <= 0) { box.style.display = 'none'; return; }
    var fee = parseFloat(sel.options[sel.selectedIndex].dataset.fee || 0);
    var f = v * fee / 100, n = v - f;
    box.style.display = 'block';
    box.innerHTML = 'Fee (' + fee + '%): <strong>{{ $sym }} ' + f.toFixed(2) + '</strong> &nbsp;|&nbsp; You will receive: <strong>{{ $sym }} ' + n.toFixed(2) + '</strong>';
}
wdCalc();
</script>

@endsection