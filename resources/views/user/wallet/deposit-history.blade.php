@extends('layouts.app')

@section('title', 'Deposit History')

@section('content')

@include('user.wallet._styles')

@php
    $currencyOptions = collect(['BDT', 'USD'])->merge($user->wallets()->pluck('currency'))->unique()->values();
@endphp

<div class="wl-page">

    <div class="wl-head">
        <div>
            <h2>Deposit History</h2>
            <p>View and track your wallet deposit requests.</p>
        </div>
        <div class="wl-crumb">
            <a href="{{ route('user.dashboard') }}">Dashboard</a><span>/</span>
            <a href="{{ route('user.wallet.index') }}">My Wallet</a><span>/</span>
            <span>Deposit History</span>
        </div>
    </div>

    @include('user.wallet._flash')

    <div class="wl-stats">
        <div class="wl-stat">
            <div class="ic"><i class="fas fa-money-bill-wave"></i></div>
            <div><p>Total Requests</p><h3>{{ number_format($summary['total_deposits']) }}</h3><small>All deposit requests</small></div>
        </div>
        <div class="wl-stat">
            <div class="ic blue"><i class="fas fa-check-circle"></i></div>
            <div>
                <p>Approved</p><h3>{{ number_format($summary['approved_deposits']) }}</h3>
                @forelse($summary['amounts_by_currency'] as $cur => $total)
                    <small>{{ \App\Support\Currency::symbol($cur) }} {{ number_format((float) $total, 2) }}</small>
                @empty
                    <small>No approved deposit yet</small>
                @endforelse
            </div>
        </div>
        <div class="wl-stat">
            <div class="ic orange"><i class="fas fa-clock"></i></div>
            <div><p>Pending</p><h3>{{ number_format($summary['pending_deposits']) }}</h3><small>Awaiting admin review</small></div>
        </div>
        <div class="wl-stat">
            <div class="ic red"><i class="fas fa-times-circle"></i></div>
            <div><p>Rejected</p><h3>{{ number_format($summary['rejected_deposits']) }}</h3><small>Rejected requests</small></div>
        </div>
    </div>

    <div class="wl-card">
        <div class="wl-card-h">
            <div><h3>Deposit Transactions</h3><p>Your complete wallet deposit history.</p></div>
            <a href="{{ route('user.deposit') }}" class="wl-pill">+ New Deposit</a>
        </div>

        <form method="GET" action="{{ route('user.deposit-history.index') }}" class="wl-filter">
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
                        <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="wl-btn primary"><i class="fas fa-filter"></i> Apply Filter</button>
            <a href="{{ route('user.deposit-history.index') }}" class="wl-btn ghost">Reset</a>
        </form>

        <div class="wl-table-wrap">
            <table class="wl-table">
                <thead>
                    <tr><th>Transaction ID</th><th>Date</th><th>Wallet</th><th>Payment Method</th><th>Amount</th><th>Status</th><th>Details</th></tr>
                </thead>
                <tbody>
                    @forelse($deposits as $d)
                        @php
                            $sym = \App\Support\Currency::symbol($d->currency);
                            $methodName = $d->paymentMethod?->name ?? strtoupper($d->method);
                            $shown = $d->status === 'approved' && $d->approved_amount !== null ? $d->approved_amount : $d->amount;
                            $payload = [
                                'title' => 'Deposit #' . $d->id,
                                'rows' => [
                                    ['Status', ucfirst($d->status)],
                                    ['Method', $methodName],
                                    ['Wallet', $d->currency],
                                    ['Requested Amount', $sym . ' ' . number_format((float) $d->amount, 2)],
                                    ['Credited Amount', $d->approved_amount !== null ? $sym . ' ' . number_format((float) $d->approved_amount, 2) : '—'],
                                    ['Sender Number', $d->sender_number],
                                    ['Transaction ID', $d->transaction_id],
                                    ['Submitted', $d->created_at->format('d M Y, h:i A')],
                                    ['Reviewed', $d->reviewed_at?->format('d M Y, h:i A')],
                                    ['Admin Note', $d->admin_note],
                                ],
                            ];
                        @endphp
                        <tr>
                            <td>{{ $d->transaction_id }}</td>
                            <td>{{ $d->created_at->format('d M Y') }}<small>{{ $d->created_at->format('h:i A') }}</small></td>
                            <td>{{ $d->currency }}</td>
                            <td>{{ $methodName }}</td>
                            <td><strong>{{ $sym }} {{ number_format((float) $shown, 2) }}</strong></td>
                            <td><span class="wl-badge {{ $d->status }}">{{ ucfirst($d->status) }}</span></td>
                            <td><button type="button" class="wl-view" data-d="{{ json_encode($payload) }}" onclick="wlOpen(this)">View</button></td>
                        </tr>
                    @empty
                        <tr><td colspan="7">
                            <div class="wl-empty">
                                <div class="ic"><i class="fas fa-receipt"></i></div>
                                <h4>No Deposit Records Found</h4>
                                <p>Your deposit requests will appear here. <a href="{{ route('user.deposit') }}">Make a deposit</a></p>
                            </div>
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($deposits->hasPages())<div class="wl-pg">{{ $deposits->links() }}</div>@endif
    </div>

    <div class="wl-notice">
        <i class="fas fa-info-circle"></i>
        <div>
            <h4>How deposits work</h4>
            <p>After you send money and submit the Transaction ID, our team verifies it. Once approved, the amount is added to your wallet and shows as <strong>Approved</strong>. Pending requests are normally reviewed within working hours.</p>
        </div>
    </div>

</div>

@include('user.wallet._modal')

@endsection