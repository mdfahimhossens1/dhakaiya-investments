@extends('layouts.app')

@section('title', 'Wallet Overview')

@section('content')

@include('user.wallet._styles')

<div class="wl-page">

    <div class="wl-head">
        <div>
            <h2>Wallet Overview</h2>
            <p>Your balances, deposits, withdrawals and income in one place.</p>
        </div>
        <div class="wl-crumb">
            <a href="{{ route('user.dashboard') }}">Dashboard</a><span>/</span><strong>My Wallet</strong>
        </div>
    </div>

    @include('user.wallet._flash')

    <div class="wl-hero">
        <div>
            <h2>💳 My Wallet</h2>
            <p>Deposits are credited after admin verification. Withdrawals are sent to your saved accounts.</p>
        </div>
        <div class="wl-hero-actions">
            <a href="{{ route('user.deposit') }}">+ Deposit</a>
            <a href="{{ route('user.withdraw') }}" class="alt">Withdraw</a>
        </div>
    </div>

    <div class="wl-stats">
        <div class="wl-stat">
            <div class="ic"><i class="fas fa-wallet"></i></div>
            <div><p>Wallets</p><h3>{{ $summary['total_wallets'] }}</h3><small>{{ $summary['active_wallets'] }} active</small></div>
        </div>
        <div class="wl-stat">
            <div class="ic blue"><i class="fas fa-arrow-down"></i></div>
            <div><p>Approved Deposits</p><h3>{{ number_format($summary['total_deposits']) }}</h3><small>Completed deposit requests</small></div>
        </div>
        <div class="wl-stat">
            <div class="ic orange"><i class="fas fa-arrow-up"></i></div>
            <div><p>Paid Withdrawals</p><h3>{{ number_format($summary['total_withdrawals']) }}</h3><small>Completed withdrawals</small></div>
        </div>
        <div class="wl-stat">
            <div class="ic purple"><i class="fas fa-exchange-alt"></i></div>
            <div><p>Transactions</p><h3>{{ number_format($summary['total_transactions']) }}</h3><small>All wallet records</small></div>
        </div>
    </div>

    <div class="wl-card-h">
        <div>
            <h3>My Wallet Accounts</h3>
            <p>One wallet per currency. Your local currency is set from your registration country.</p>
        </div>
        <span class="wl-pill">{{ count($wallets) }} Wallets</span>
    </div>

    <div class="wl-wallets">
        @foreach($wallets as $w)
            <div class="wl-wcard">
                <div class="wl-wcard-top">
                    <div class="wl-wicon">{{ $w['icon'] }}</div>
                    <span class="wl-badge approved">{{ $w['status'] }}</span>
                </div>

                <h3>{{ $w['name'] }}</h3>
                <p>{{ $w['description'] }}</p>

                <div class="wl-bal-l">AVAILABLE BALANCE</div>
                <div class="wl-bal"><span>{{ $w['symbol'] }}</span>{{ number_format((float) $w['balance'], 2) }}</div>

                <div class="wl-mini">
                    <div><span>Total Deposited</span><strong>{{ $w['symbol'] }} {{ number_format((float) $w['total_deposit'], 2) }}</strong></div>
                    <div><span>Total Withdrawn</span><strong>{{ $w['symbol'] }} {{ number_format((float) $w['total_withdraw'], 2) }}</strong></div>
                    <div><span>Pending Withdrawal</span><strong>{{ $w['symbol'] }} {{ number_format((float) $w['pending_withdraw'], 2) }}</strong></div>
                    <div><span>Total Income</span><strong class="wl-pos">{{ $w['symbol'] }} {{ number_format((float) $w['total_income'], 2) }}</strong></div>
                </div>

                <div class="wl-wact">
                    <a href="{{ route('user.deposit', ['currency' => $w['currency']]) }}">Deposit</a>
                    <a href="{{ route('user.withdraw', ['currency' => $w['currency']]) }}" class="alt">Withdraw</a>
                </div>
            </div>
        @endforeach
    </div>

    <div class="wl-card">
        <div class="wl-card-h">
            <div><h3>Recent Transactions</h3><p>Your latest 10 wallet activities.</p></div>
            <a href="{{ route('user.transaction-history.index') }}" class="wl-pill">View All</a>
        </div>

        <div class="wl-table-wrap">
            <table class="wl-table">
                <thead>
                    <tr><th>Date</th><th>Type</th><th>Wallet</th><th>Amount</th><th>Balance After</th></tr>
                </thead>
                <tbody>
                    @forelse($recentTransactions as $t)
                        @php $sym = \App\Support\Currency::symbol($t->currency); $in = $t->direction === 'credit'; @endphp
                        <tr>
                            <td>{{ $t->created_at->format('d M Y') }}<small>{{ $t->created_at->format('h:i A') }}</small></td>
                            <td>{{ $t->typeLabel() }}@if($t->description)<small>{{ \Illuminate\Support\Str::limit($t->description, 50) }}</small>@endif</td>
                            <td>{{ $t->currency }}</td>
                            <td class="{{ $in ? 'wl-pos' : 'wl-neg' }}">{{ $in ? '+' : '−' }}{{ $sym }} {{ number_format((float) $t->amount, 2) }}</td>
                            <td>{{ $sym }} {{ number_format((float) $t->balance_after, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5">
                            <div class="wl-empty">
                                <div class="ic"><i class="fas fa-exchange-alt"></i></div>
                                <h4>No transactions yet</h4>
                                <p>Make your first deposit to get started.</p>
                            </div>
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="wl-notice">
        <i class="fas fa-shield-alt"></i>
        <div>
            <h4>Wallet Security Notice</h4>
            <p>Never share your password, PIN or OTP with anyone. Our team will never ask for them. Always send money only to the account shown on the Deposit page.</p>
        </div>
    </div>

</div>

@endsection