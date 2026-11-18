@extends('layouts.app')

@section('title', 'Profit & Loss History')

@section('content')

@include('user.investment._styles')

@php $sym = \App\Support\Currency::symbol($currency); @endphp

<div class="inv-page">

    <div class="inv-heading">
        <div>
            <h2>Profit & Loss History</h2>
            <p>Your daily profit from shares (credited to your withdrawable earnings) and your resale results.</p>
        </div>
        <span class="inv-badge"><i class="fas fa-chart-line"></i> My Profit</span>
    </div>

    <div class="inv-summary">
        <div class="inv-stat">
            <div class="icon"><i class="fas fa-gift"></i></div>
            <div class="label">Total Daily Profit Received</div>
            <h3>৳ {{ number_format((float) $summary['total_profit_bdt'], 2) }}</h3>
            <small>
                @forelse($summary['by_currency'] as $cur => $t){{ \App\Support\Currency::symbol($cur) }} {{ number_format((float) $t, 2) }} {{ $cur }}@if(! $loop->last) · @endif
                @empty No profit received yet @endforelse
            </small>
        </div>
        <div class="inv-stat">
            <div class="icon"><i class="fas fa-calendar-alt"></i></div>
            <div class="label">This Month</div>
            <h3>৳ {{ number_format((float) $summary['this_month_bdt'], 2) }}</h3>
            <small>
                @if($summary['last_profit_date'])
                    Last credited {{ \Illuminate\Support\Carbon::parse($summary['last_profit_date'])->format('d M Y') }}: ৳ {{ number_format((float) $summary['last_profit_bdt'], 2) }}
                @else Waiting for your first profit @endif
            </small>
        </div>
        <div class="inv-stat">
            <div class="icon"><i class="fas fa-hourglass-half"></i></div>
            <div class="label">Expected Per Day</div>
            <h3>৳ {{ number_format((float) $expected, 2) }}</h3>
            <small>
                @if($expectedLocal !== null && $currency !== 'BDT')≈ {{ $sym }} {{ number_format((float) $expectedLocal, 2) }} · @endif
                @if(bccomp($boost, '0', 2) > 0)incl. +{{ rtrim(rtrim($boost, '0'), '.') }}% rank boost · @endif
                from your current shares
            </small>
        </div>
        <div class="inv-stat">
            <div class="icon"><i class="fas fa-balance-scale"></i></div>
            <div class="label">Resale Profit / Loss</div>
            <h3 class="{{ (float) $summary['resale_profit_loss_bdt'] >= 0 ? 'pl-pos' : 'pl-neg' }}">
                {{ (float) $summary['resale_profit_loss_bdt'] >= 0 ? '+' : '' }}{{ number_format((float) $summary['resale_profit_loss_bdt'], 2) }}
            </h3>
            <small>BDT, from shares you resold</small>
        </div>
    </div>

    @if(count($expectedRows))
    <div class="inv-panel">
        <div class="inv-panel-header">
            <h4>Your Daily Earning Shares</h4>
            <p>Profit for a day is credited after midnight (Bangladesh time). A share earns for each full day you hold it, so a new purchase starts earning from the next day.</p>
        </div>
        <div class="inv-table-wrap">
            <table class="inv-table" style="min-width:600px">
                <thead><tr><th>Project</th><th>Shares</th><th>Daily %</th><th>Per Day (BDT)</th></tr></thead>
                <tbody>
                @foreach($expectedRows as $r)
                    <tr>
                        <td><strong>{{ $r->project }}</strong></td>
                        <td>{{ number_format($r->shares) }}</td>
                        <td>{{ rtrim(rtrim((string) $r->percent, '0'), '.') }}%</td>
                        <td class="pl-pos">+{{ number_format((float) $r->daily_bdt, 2) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <div class="inv-panel">
        <form method="GET" action="{{ route('user.profit-loss-history.index') }}" class="inv-filter">
            <input type="date" name="from" value="{{ request('from') }}" style="padding:10px 13px;border:1px solid var(--border);border-radius:8px">
            <input type="date" name="to" value="{{ request('to') }}" style="padding:10px 13px;border:1px solid var(--border);border-radius:8px">
            <button type="submit" class="inv-btn primary"><i class="fas fa-filter"></i> Apply</button>
            <a href="{{ route('user.profit-loss-history.index') }}" class="inv-btn outline">Reset</a>
        </form>

        <div class="inv-panel-header"><h4>Daily Profit Records</h4><p>Each entry was added to your wallet's earnings.</p></div>

        <div class="inv-table-wrap">
            <table class="inv-table" style="min-width:820px">
                <thead><tr><th>Profit Date</th><th>Project</th><th>Shares</th><th>Daily %</th><th>Profit (BDT)</th><th>Credited To Wallet</th></tr></thead>
                <tbody>
                @forelse($profits as $p)
                    <tr>
                        <td>{{ $p->profit_date->format('d M Y') }}</td>
                        <td>{{ $p->project->name ?? 'Removed project' }}</td>
                        <td>{{ number_format($p->shares) }}</td>
                        <td>{{ rtrim(rtrim((string) $p->profit_percent, '0'), '.') }}%</td>
                        <td class="pl-pos">+{{ number_format((float) $p->profit_bdt, 2) }}</td>
                        <td><strong>{{ \App\Support\Currency::symbol($p->wallet_currency) }} {{ number_format((float) $p->wallet_amount, 2) }}</strong> {{ $p->wallet_currency }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6"><div class="inv-empty"><div class="icon"><i class="fas fa-gift"></i></div>
                        <h4>No profit yet</h4>
                        <p>Your first profit arrives the night after your shares have been held for a full day.</p></div></td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($profits->hasPages())<div class="inv-pagination">{{ $profits->links() }}</div>@endif
    </div>

</div>

@endsection