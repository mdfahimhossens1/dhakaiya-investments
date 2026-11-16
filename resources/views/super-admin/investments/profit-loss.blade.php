@extends('layouts.app')

@section('title', 'Profit & Loss History')

@section('content')

@include('user.investment._styles')

<style>
    .f-input { padding:10px 13px; border:1px solid var(--border); border-radius:8px; font-size:13px; outline:none; background:#fff; }
    .chips { display:flex; flex-wrap:wrap; gap:8px; margin-top:10px; }
    .chip { background:#e8f5ef; color:var(--primary); padding:5px 10px; border-radius:20px; font-size:11px; font-weight:600; }
</style>

<div class="inv-page">

    <div class="inv-heading">
        <div>
            <h2>Profit & Loss History</h2>
            <p>Daily profit paid to users and profit/loss from resales.</p>
        </div>
        <span class="inv-badge"><i class="fas fa-chart-line"></i> All Users</span>
    </div>

    {{-- Summary --}}
    <div class="inv-summary">
        <div class="inv-stat">
            <div class="icon"><i class="fas fa-gift"></i></div>
            <div class="label">Total Daily Profit Paid (BDT)</div>
            <h3>{{ number_format((float) $summary['profit_total'], 2) }}</h3>
            <small>{{ number_format($summary['log_count']) }} log entries</small>
        </div>
        <div class="inv-stat">
            <div class="icon"><i class="fas fa-calendar-alt"></i></div>
            <div class="label">This Month (BDT)</div>
            <h3>{{ number_format((float) $summary['profit_month'], 2) }}</h3>
            <small>
                @if($summary['latest_date'])
                    Last run {{ \Illuminate\Support\Carbon::parse($summary['latest_date'])->format('d M Y') }}:
                    {{ number_format((float) $summary['latest_amount'], 2) }}
                @else
                    No profit run yet
                @endif
            </small>
        </div>
        <div class="inv-stat">
            <div class="icon"><i class="fas fa-balance-scale"></i></div>
            <div class="label">Net Resale P/L (BDT)</div>
            <h3 class="{{ (float) $summary['resale_net'] >= 0 ? 'pl-pos' : 'pl-neg' }}">
                {{ (float) $summary['resale_net'] >= 0 ? '+' : '' }}{{ number_format((float) $summary['resale_net'], 2) }}
            </h3>
            <small>All resales</small>
        </div>
        <div class="inv-stat">
            <div class="icon"><i class="fas fa-arrows-alt-v"></i></div>
            <div class="label">Resale Gross Profit / Loss</div>
            <h3 style="font-size:16px;">
                <span class="pl-pos">+{{ number_format((float) $summary['gross_profit'], 2) }}</span><br>
                <span class="pl-neg">{{ number_format((float) $summary['gross_loss'], 2) }}</span>
            </h3>
            <small>{{ $summary['profit_trades'] }} profit · {{ $summary['loss_trades'] }} loss sale(s)</small>
        </div>
    </div>

    @if($summary['by_currency']->count())
        <div class="inv-panel">
            <div class="inv-panel-body">
                <strong style="font-size:13px; color:var(--text);">Daily profit paid, by wallet currency</strong>
                <div class="chips">
                    @foreach($summary['by_currency'] as $currency => $total)
                        <span class="chip">{{ $currency }}: {{ number_format((float) $total, 2) }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- Filters --}}
    <div class="inv-panel">
        <form method="GET" action="{{ route('super-admin.investments.profit-loss') }}" class="inv-filter" style="border-bottom:none;">
            <input type="text" name="search" value="{{ $search }}" placeholder="User name or email..." class="f-input" style="min-width:210px;">
            <input type="date" name="from" value="{{ $from }}" class="f-input" title="Daily profit from date">
            <input type="date" name="to" value="{{ $to }}" class="f-input" title="Daily profit to date">
            <select name="result">
                <option value="all" {{ $result === 'all' ? 'selected' : '' }}>Resales: All</option>
                <option value="profit" {{ $result === 'profit' ? 'selected' : '' }}>Resales: Profit only</option>
                <option value="loss" {{ $result === 'loss' ? 'selected' : '' }}>Resales: Loss only</option>
            </select>
            <button type="submit" class="inv-btn primary"><i class="fas fa-filter"></i> Apply</button>
            <a href="{{ route('super-admin.investments.profit-loss') }}" class="inv-btn outline">Reset</a>
        </form>
    </div>

    {{-- Daily profit logs --}}
    <div class="inv-panel">
        <div class="inv-panel-header">
            <h4>Daily Profit Logs</h4>
            <p>Profit credited to users' wallets (date and search filters apply).</p>
        </div>

        <div class="inv-table-wrap">
            <table class="inv-table" style="min-width:850px;">
                <thead>
                    <tr>
                        <th>#</th><th>User</th><th>Project</th><th>Profit Date</th>
                        <th>Profit (BDT)</th><th>Credited To Wallet</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->id }}</td>
                            <td>{{ $log->user->name ?? '—' }}<small>{{ $log->user->email ?? '' }}</small></td>
                            <td>{{ $log->project->name ?? '—' }}</td>
                            <td>{{ \Illuminate\Support\Carbon::parse($log->profit_date)->format('d M Y') }}</td>
                            <td class="pl-pos">+{{ number_format((float) $log->profit_bdt, 2) }}</td>
                            <td>
                                @if($log->wallet_amount)
                                    {{ number_format((float) $log->wallet_amount, 2) }} {{ $log->wallet_currency }}
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="inv-empty">
                                    <div class="icon"><i class="fas fa-gift"></i></div>
                                    <h4>No Daily Profit Logs</h4>
                                    <p>No profit log matches your filters.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="inv-pagination">{{ $logs->links() }}</div>
        @endif
    </div>

    {{-- Resale profit / loss --}}
    <div class="inv-panel">
        <div class="inv-panel-header">
            <h4>Resale Profit / Loss</h4>
            <p>Result of shares users have resold.</p>
        </div>

        <div class="inv-table-wrap">
            <table class="inv-table" style="min-width:900px;">
                <thead>
                    <tr>
                        <th>Sale ID</th><th>User</th><th>Project</th><th>Shares</th>
                        <th>Received (BDT)</th><th>Profit / Loss (BDT)</th><th>Date</th><th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                        @php $pl = (float) $sale->profit_loss_bdt; @endphp
                        <tr>
                            <td>#{{ $sale->id }}</td>
                            <td>{{ $sale->user->name ?? '—' }}<small>{{ $sale->user->email ?? '' }}</small></td>
                            <td>{{ $sale->project->name ?? '—' }}</td>
                            <td>{{ number_format($sale->shares) }}</td>
                            <td>{{ number_format((float) $sale->net_bdt, 2) }}</td>
                            <td class="{{ $pl >= 0 ? 'pl-pos' : 'pl-neg' }}">{{ $pl >= 0 ? '+' : '' }}{{ number_format($pl, 2) }}</td>
                            <td>{{ $sale->created_at->format('d M Y') }}<small>{{ $sale->created_at->format('h:i A') }}</small></td>
                            <td><span class="st-badge {{ $sale->status }}">{{ ucfirst($sale->status) }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="inv-empty">
                                    <div class="icon"><i class="fas fa-chart-line"></i></div>
                                    <h4>No Resale Records</h4>
                                    <p>No resale matches your filters.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($sales->hasPages())
            <div class="inv-pagination">{{ $sales->links() }}</div>
        @endif
    </div>

</div>

@endsection