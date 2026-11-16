@extends('layouts.app')

@section('title', 'Withdrawal History')

@section('content')

@include('user.wallet._styles')

@php
    $currencyOptions = collect(['BDT', 'USD'])->merge($user->wallets()->pluck('currency'))->unique()->values();
@endphp

<div class="wl-page">

    <div class="wl-head">
        <div>
            <h2>Withdrawal History</h2>
            <p>View and track your withdrawal requests.</p>
        </div>
        <div class="wl-crumb">
            <a href="{{ route('user.dashboard') }}">Dashboard</a><span>/</span>
            <a href="{{ route('user.wallet.index') }}">My Wallet</a><span>/</span>
            <span>Withdrawal History</span>
        </div>
    </div>

    @include('user.wallet._flash')

    <div class="wl-stats">
        <div class="wl-stat">
            <div class="ic"><i class="fas fa-wallet"></i></div>
            <div><p>Total Requests</p><h3>{{ number_format($summary['total_withdrawals']) }}</h3><small>All withdrawal requests</small></div>
        </div>
        <div class="wl-stat">
            <div class="ic orange"><i class="fas fa-clock"></i></div>
            <div><p>Pending</p><h3>{{ number_format($summary['pending_withdrawals']) }}</h3><small>Amount is on hold</small></div>
        </div>
        <div class="wl-stat">
            <div class="ic blue"><i class="fas fa-check-circle"></i></div>
            <div>
                <p>Paid</p><h3>{{ number_format($summary['approved_withdrawals']) }}</h3>
                @forelse($summary['amounts_by_currency'] as $cur => $total)
                    <small>{{ \App\Support\Currency::symbol($cur) }} {{ number_format((float) $total, 2) }}</small>
                @empty
                    <small>Nothing paid yet</small>
                @endforelse
            </div>
        </div>
        <div class="wl-stat">
            <div class="ic red"><i class="fas fa-times-circle"></i></div>
            <div><p>Rejected</p><h3>{{ number_format($summary['rejected_withdrawals']) }}</h3><small>Refunded to wallet</small></div>
        </div>
    </div>

    <div class="wl-card">
        <div class="wl-card-h">
            <div><h3>Withdrawal Transactions</h3><p>Your complete withdrawal history.</p></div>
            <a href="{{ route('user.withdraw') }}" class="wl-pill">+ New Withdrawal</a>
        </div>

        <form method="GET" action="{{ route('user.withdrawal-history.index') }}" class="wl-filter">
            <div class="wl-field">
                <label for="currency">Currency</label>
                <select name="currency" id="currency">
                    <option value="all" @selected($currency === 'all')>All Currencies</option>
                    @foreach($currencyOptions as $c)
                        <option value="{{ $c }}" @selected($currency === $c)>{{ $c }}</option>
                    @endforeach
                </select>
            </div>
            <div class="wl-field">
                <label for="status">Status</label>
                <select name="status" id="status">
                    <option value="">All Status</option>
                    @foreach(['pending', 'approved', 'rejected'] as $s)
                        <option value="{{ $s }}" @selected(request('status') === $s)>{{ $s === 'approved' ? 'Paid' : ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="wl-btn primary"><i class="fas fa-filter"></i> Apply Filter</button>
            <a href="{{ route('user.withdrawal-history.index') }}" class="wl-btn ghost">Reset</a>
        </form>

        <div class="wl-table-wrap">
            <table class="wl-table">
                <thead>
                    <tr><th>Request ID</th><th>Date</th><th>Wallet</th><th>Payment Method</th><th>Amount</th><th>You Receive</th><th>Status</th><th>Details</th></tr>
                </thead>
                <tbody>
                    @forelse($withdrawals as $w)
                        @php
                            $sym = \App\Support\Currency::symbol($w->currency);
                            $methodName = $w->paymentMethod?->name ?? strtoupper($w->method);
                            $acc = (string) $w->account_number;
                            $masked = strlen($acc) > 4 ? str_repeat('*', min(8, strlen($acc) - 4)) . substr($acc, -4) : $acc;
                            $payload = [
                                'title' => 'Withdrawal #WR-' . $w->id,
                                'rows' => [
                                    ['Status', $w->status === 'approved' ? 'Paid' : ucfirst($w->status)],
                                    ['Method', $methodName],
                                    ['Account Name', $w->account_name],
                                    ['Account', $masked],
                                    ['Bank', $w->bank_name],
                                    ['Amount', $sym . ' ' . number_format((float) $w->amount, 2)],
                                    ['Fee', $sym . ' ' . number_format((float) $w->fee, 2)],
                                    ['You Receive', $sym . ' ' . number_format((float) $w->net_amount, 2)],
                                    ['Payout Reference', $w->payout_reference],
                                    ['Requested', $w->created_at->format('d M Y, h:i A')],
                                    ['Processed', $w->reviewed_at?->format('d M Y, h:i A')],
                                    ['Admin Note', $w->admin_note],
                                ],
                            ];
                        @endphp
                        <tr>
                            <td>#WR-{{ $w->id }}</td>
                            <td>{{ $w->created_at->format('d M Y') }}<small>{{ $w->created_at->format('h:i A') }}</small></td>
                            <td>{{ $w->currency }}</td>
                            <td>{{ $methodName }}<small>{{ $masked }}</small></td>
                            <td>{{ $sym }} {{ number_format((float) $w->amount, 2) }}</td>
                            <td><strong>{{ $sym }} {{ number_format((float) $w->net_amount, 2) }}</strong></td>
                            <td><span class="wl-badge {{ $w->status }}">{{ $w->status === 'approved' ? 'Paid' : ucfirst($w->status) }}</span></td>
                            <td><button type="button" class="wl-view" data-d="{{ json_encode($payload) }}" onclick="wlOpen(this)">View</button></td>
                        </tr>
                    @empty
                        <tr><td colspan="8">
                            <div class="wl-empty">
                                <div class="ic"><i class="fas fa-wallet"></i></div>
                                <h4>No Withdrawal Records Found</h4>
                                <p>Your withdrawal requests will appear here. <a href="{{ route('user.withdraw') }}">Request a withdrawal</a></p>
                            </div>
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($withdrawals->hasPages())<div class="wl-pg">{{ $withdrawals->links() }}</div>@endif
    </div>

    <div class="wl-notice">
        <i class="fas fa-shield-alt"></i>
        <div>
            <h4>Withdrawal Information</h4>
            <p>When you request a withdrawal, the amount is held from your wallet immediately. After our team sends the money, the request is marked <strong>Paid</strong>. If a request is rejected, the full amount returns to your wallet.</p>
        </div>
    </div>

</div>

@include('user.wallet._modal')

@endsection