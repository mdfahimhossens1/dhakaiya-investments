@extends('layouts.app')

@section('title', 'Marketplace History')

@section('content')

@include('user.wallet._styles')

<div class="wl-page">

    <div class="wl-head">
        <div>
            <h2>Marketplace History</h2>
            <p>Shares you bought from or sold to other members.</p>
        </div>
        <div class="wl-crumb">
            <a href="{{ route('user.resell.index') }}">Resell Share</a><span>/</span><strong>History</strong>
        </div>
    </div>

    @include('user.wallet._flash')

    <div class="wl-card">
        <div class="wl-table-wrap">
            <table class="wl-table">
                <thead>
                    <tr><th>Date</th><th>Type</th><th>Project</th><th>Shares</th><th>With</th><th>Amount</th><th>Commission</th></tr>
                </thead>
                <tbody>
                    @forelse($trades as $t)
                        @php
                            $sold = $t->seller_id === $user->id;
                            $cur = $sold ? $t->seller_currency : $t->buyer_currency;
                            $sym = \App\Support\Currency::symbol($cur);
                            $amount = $sold ? $t->seller_amount : $t->buyer_amount;
                            $other = $sold ? $t->buyer : $t->seller;
                        @endphp
                        <tr>
                            <td>{{ $t->created_at->format('d M Y') }}<small>{{ $t->created_at->format('h:i A') }}</small></td>
                            <td><span class="wl-badge {{ $sold ? 'approved' : 'pending' }}">{{ $sold ? 'Sold' : 'Bought' }}</span></td>
                            <td>{{ $t->project?->name }}</td>
                            <td>{{ number_format($t->shares) }}</td>
                            <td>{{ \Illuminate\Support\Str::mask($other?->name ?? 'Member', '*', 2) }}</td>
                            <td class="{{ $sold ? 'wl-pos' : 'wl-neg' }}">{{ $sold ? '+' : '−' }}{{ $sym }} {{ number_format((float) $amount, 2) }}</td>
                            <td>{{ $sold ? (float) $t->fee_percent + 0 . '%' : '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7">
                            <div class="wl-empty">
                                <div class="ic"><i class="fas fa-handshake"></i></div>
                                <h4>No marketplace trades yet</h4>
                                <p>Your completed purchases and sales will appear here.</p>
                            </div>
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($trades->hasPages())
            <div style="margin-top:16px;">{{ $trades->links() }}</div>
        @endif
    </div>

</div>

@endsection