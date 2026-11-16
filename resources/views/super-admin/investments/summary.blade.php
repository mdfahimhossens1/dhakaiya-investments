@extends('layouts.app')

@section('title', 'Investment Summary')

@section('content')

@include('user.investment._styles')

<div class="inv-page">

    <div class="inv-heading">
        <div>
            <h2>Investment Summary</h2>
            <p>Platform-wide overview of all user investments.</p>
        </div>
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <a href="{{ route('super-admin.investments.project-wise') }}" class="inv-btn outline">Project-wise</a>
            <a href="{{ route('super-admin.investments.profit-loss') }}" class="inv-btn outline">Profit &amp; Loss</a>
            <a href="{{ route('super-admin.investments.maturity') }}" class="inv-btn outline">Maturity</a>
        </div>
    </div>

    <div class="inv-summary">
        <div class="inv-stat">
            <div class="icon"><i class="fas fa-users"></i></div>
            <div class="label">Active Investors</div>
            <h3>{{ number_format($summary['investors']) }}</h3>
            <small>Users holding shares</small>
        </div>
        <div class="inv-stat">
            <div class="icon"><i class="fas fa-coins"></i></div>
            <div class="label">Total Invested (BDT)</div>
            <h3>{{ number_format((float) $summary['invested'], 2) }}</h3>
            <small>{{ number_format($summary['shares_held']) }} shares held</small>
        </div>
        <div class="inv-stat">
            <div class="icon"><i class="fas fa-chart-line"></i></div>
            <div class="label">Current Value (BDT)</div>
            <h3>{{ number_format((float) $summary['current_value'], 2) }}</h3>
            <small class="{{ (float) $summary['unrealized'] >= 0 ? 'pl-pos' : 'pl-neg' }}">
                {{ (float) $summary['unrealized'] >= 0 ? '+' : '' }}{{ number_format((float) $summary['unrealized'], 2) }} unrealized
            </small>
        </div>
        <div class="inv-stat">
            <div class="icon"><i class="fas fa-gift"></i></div>
            <div class="label">Daily Profit Paid (BDT)</div>
            <h3>{{ number_format((float) $summary['profit_paid_total'], 2) }}</h3>
            <small>{{ number_format((float) $summary['profit_paid_month'], 2) }} this month</small>
        </div>
    </div>

    <div class="inv-summary">
        <div class="inv-stat">
            <div class="icon"><i class="fas fa-shopping-cart"></i></div>
            <div class="label">Buy Orders</div>
            <h3>{{ number_format($summary['buy_count']) }}</h3>
            <small>{{ number_format((float) $summary['buy_volume'], 2) }} BDT volume</small>
        </div>
        <div class="inv-stat">
            <div class="icon"><i class="fas fa-hand-holding-usd"></i></div>
            <div class="label">Sell Orders</div>
            <h3>{{ number_format($summary['sell_count']) }}</h3>
            <small>{{ number_format((float) $summary['sell_volume'], 2) }} BDT volume</small>
        </div>
        <div class="inv-stat">
            <div class="icon"><i class="fas fa-check-double"></i></div>
            <div class="label">Resale P/L (BDT)</div>
            <h3 class="{{ (float) $summary['resale_pl'] >= 0 ? 'pl-pos' : 'pl-neg' }}">
                {{ (float) $summary['resale_pl'] >= 0 ? '+' : '' }}{{ number_format((float) $summary['resale_pl'], 2) }}
            </h3>
            <small>Realized from resales</small>
        </div>
        <div class="inv-stat">
            <div class="icon"><i class="fas fa-clock"></i></div>
            <div class="label">Pending Orders</div>
            <h3>{{ number_format($summary['pending_orders']) }}</h3>
            <small>
                @if($summary['latest_profit_date'])
                    Last profit run: {{ \Illuminate\Support\Carbon::parse($summary['latest_profit_date'])->format('d M Y') }}
                    ({{ number_format((float) $summary['latest_profit_amount'], 2) }} BDT)
                @else
                    No profit run yet
                @endif
            </small>
        </div>
    </div>

    <div class="inv-panel">
        <div class="inv-panel-header">
            <h4>Top Projects by Investment</h4>
            <p>Top 5 projects by invested amount.</p>
        </div>
        <div class="inv-table-wrap">
            <table class="inv-table" style="min-width:650px;">
                <thead>
                    <tr><th>Project</th><th>Holders</th><th>Shares Held</th><th>Invested (BDT)</th><th>Current Value (BDT)</th></tr>
                </thead>
                <tbody>
                    @forelse($topProjects as $p)
                        <tr>
                            <td><strong>{{ $p['name'] }}</strong></td>
                            <td>{{ number_format($p['holders']) }}</td>
                            <td>{{ number_format($p['shares']) }}</td>
                            <td>{{ number_format($p['invested'], 2) }}</td>
                            <td>{{ number_format($p['current'], 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5"><div class="inv-empty"><p style="margin:0;">No investments yet.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="inv-panel">
        <div class="inv-panel-header">
            <h4>Recent Orders</h4>
            <p>Latest 10 buy and sell orders across the platform.</p>
        </div>
        <div class="inv-table-wrap">
            <table class="inv-table" style="min-width:800px;">
                <thead>
                    <tr><th>Order</th><th>User</th><th>Type</th><th>Project</th><th>Shares</th><th>Net (BDT)</th><th>Date</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @forelse($recent as $o)
                        <tr>
                            <td>#{{ $o->id }}</td>
                            <td>{{ $o->user->name ?? '—' }}<small>{{ $o->user->email ?? '' }}</small></td>
                            <td><span class="st-badge {{ $o->type === 'buy' ? 'approved' : 'pending' }}">{{ ucfirst($o->type) }}</span></td>
                            <td>{{ $o->project->name ?? '—' }}</td>
                            <td>{{ number_format($o->shares) }}</td>
                            <td>{{ number_format((float) $o->net_bdt, 2) }}</td>
                            <td>{{ $o->created_at->format('d M Y') }}</td>
                            <td><span class="st-badge {{ $o->status }}">{{ ucfirst($o->status) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="8"><div class="inv-empty"><p style="margin:0;">No orders yet.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection