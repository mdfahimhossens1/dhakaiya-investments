@extends('layouts.app')

@section('title', 'Wallet Overview')

@section('content')

@include('user.wallet._styles')

<div class="wl-page">

    <div class="wl-head">
        <div>
            <h2>Wallet Overview</h2>
            <p>Your balance, deposits, withdrawals and income in one place.</p>
        </div>
        <div class="wl-crumb">
            <a href="{{ route('user.dashboard') }}">Dashboard</a><span>/</span><strong>My Wallet</strong>
        </div>
    </div>

    @include('user.wallet._flash')

    <div class="wl-hero">
        <div>
            <h2>💳 My Wallet</h2>
            <p>Deposits are credited after admin verification. Your whole wallet balance can be used to buy shares or withdrawn to your saved accounts.</p>
        </div>
        <div class="wl-hero-actions">
            <a href="{{ route('user.deposit') }}">+ Deposit</a>
            <a href="{{ route('user.withdraw') }}" class="alt">Withdraw</a>
        </div>
    </div>

    <div class="wl-stats">
        <div class="wl-stat">
            <div class="ic"><i class="fas fa-wallet"></i></div>
            <div><p>Wallets</p><h3>{{ $summary['total_wallets'] }}</h3><small>One balance, shown in {{ $summary['active_wallets'] }} {{ $summary['active_wallets'] > 1 ? 'currencies' : 'currency' }}</small></div>
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
            <p>You have one balance. The other card shows the same balance converted at the current exchange rate.</p>
        </div>
        <span class="wl-pill">{{ count($wallets) }} {{ count($wallets) > 1 ? 'Views' : 'View' }}</span>
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

                <div class="wl-bal-l">TOTAL BALANCE</div>
                <div class="wl-bal"><span>{{ $w['symbol'] }}</span>{{ number_format((float) $w['balance'], 2) }}</div>

                @if($w['display_only'])
                    <div style="margin:-12px 0 16px;font-size:12px;color:var(--muted)">
                        @if($w['rate_missing'])
                            USD rate not set
                        @else
                            Display only · converted from your {{ $wallets[0]['currency'] }} balance
                        @endif
                    </div>
                @endif

                <div class="wl-mini">
                    <div><span>Available to Buy Shares</span><strong class="wl-pos">{{ $w['symbol'] }} {{ number_format((float) $w['balance'], 2) }}</strong></div>
                    <div><span>Available to Withdraw</span><strong class="wl-pos">{{ $w['symbol'] }} {{ number_format((float) $w['balance'], 2) }}</strong></div>
                    <div><span>Total Deposited</span><strong>{{ $w['symbol'] }} {{ number_format((float) $w['total_deposit'], 2) }}</strong></div>
                    <div><span>Total Withdrawn</span><strong>{{ $w['symbol'] }} {{ number_format((float) $w['total_withdraw'], 2) }}</strong></div>
                    <div><span>Pending Withdrawal</span><strong>{{ $w['symbol'] }} {{ number_format((float) $w['pending_withdraw'], 2) }}</strong></div>
                    <div><span>Total Income</span><strong class="wl-pos">{{ $w['symbol'] }} {{ number_format((float) $w['total_income'], 2) }}</strong></div>
                </div>

                <div class="wl-wact">
                    <a href="{{ route('user.deposit') }}">Deposit</a>
                    <a href="{{ route('user.withdraw') }}" class="alt">Withdraw</a>
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
            <h4>How your balance works</h4>
            <p>You have <strong>one wallet balance</strong>, kept in {{ $wallets[0]['currency'] }}. @if(count($wallets) > 1)The USD card is the same balance converted at the current exchange rate, so the two always match. @endif Everything goes into this one wallet: deposits, daily profit, referral commission, rewards and share resale. You can use <strong>all of it</strong> to buy shares, or withdraw it to your saved accounts. Never share your password, PIN or OTP with anyone.</p>
        </div>
    </div>

</div>

@endsection