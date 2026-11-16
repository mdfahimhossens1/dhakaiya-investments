@extends('layouts.app')

@section('title', 'Sales History')

@section('content')

@include('partials.styles')

<div class="inv-page">

    {{-- Heading --}}
    <div class="inv-heading">
        <div>
            <h2>Sales History</h2>
            <p>View your previous share resale records.</p>
        </div>

        <span class="inv-badge"><i class="fas fa-hand-holding-usd"></i> Sales Records</span>
    </div>

    @if(session('success'))
        <div class="inv-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="inv-alert error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
    @endif


    @php
        $totalPL = (float) $summary['total_profit_loss_bdt'];
    @endphp

    {{-- Summary --}}
    <div class="inv-summary">

        <div class="inv-stat">
            <div class="icon"><i class="fas fa-receipt"></i></div>
            <div class="label">Total Sales</div>
            <h3>{{ number_format($summary['total_records']) }}</h3>
            <small>{{ number_format($summary['total_shares']) }} shares sold</small>
        </div>

        <div class="inv-stat">
            <div class="icon"><i class="fas fa-coins"></i></div>
            <div class="label">Total Received (BDT)</div>
            <h3>{{ number_format((float) $summary['total_received_bdt'], 2) }}</h3>
            <small>Net amount</small>
        </div>

        <div class="inv-stat">
            <div class="icon"><i class="fas fa-chart-line"></i></div>
            <div class="label">Total Profit / Loss (BDT)</div>
            <h3 class="{{ $totalPL >= 0 ? 'pl-pos' : 'pl-neg' }}">
                {{ $totalPL >= 0 ? '+' : '' }}{{ number_format($totalPL, 2) }}
            </h3>
            <small>From resales</small>
        </div>

        <div class="inv-stat">
            <div class="icon"><i class="fas fa-check-circle"></i></div>
            <div class="label">Approved / Completed</div>
            <h3>{{ number_format($summary['approved'] + $summary['completed']) }}</h3>
            <small>{{ number_format($summary['pending']) }} pending · {{ number_format($summary['rejected']) }} rejected</small>
        </div>

    </div>


    {{-- Records --}}
    <div class="inv-panel">

        <div class="inv-panel-header">
            <h4>My Sales Records</h4>
            <p>Review your share resale history.</p>
        </div>

        <form method="GET" action="{{ route('user.sales-history.index') }}" class="inv-filter">

            <select name="status">
                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Status</option>
                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>

            <button type="submit" class="inv-btn primary"><i class="fas fa-filter"></i> Apply Filter</button>

            <a href="{{ route('user.sales-history.index') }}" class="inv-btn outline">Reset</a>

        </form>

        <div class="inv-table-wrap">

            <table class="inv-table" style="min-width:900px;">

                <thead>
                    <tr>
                        <th>Sale ID</th>
                        <th>Project</th>
                        <th>Shares</th>
                        <th>Net Amount (BDT)</th>
                        <th>Credited To Wallet</th>
                        <th>Profit / Loss (BDT)</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($sales as $sale)

                        @php $pl = (float) $sale->profit_loss_bdt; @endphp

                        <tr>
                            <td>#{{ $sale->id }}</td>

                            <td>
                                @if($sale->project)
                                    <a href="{{ route('user.project-details.show', $sale->project->slug) }}"
                                       style="color:var(--text); font-weight:600; text-decoration:none;">
                                        {{ $sale->project->name }}
                                    </a>
                                    <small>{{ $sale->project->company->name ?? 'N/A' }}</small>
                                @else
                                    <span style="color:var(--muted);">Project removed</span>
                                @endif
                            </td>

                            <td><strong>{{ number_format($sale->shares) }}</strong></td>

                            <td>{{ number_format((float) $sale->net_bdt, 2) }}</td>

                            <td>
                                @if($sale->wallet_amount)
                                    {{ number_format((float) $sale->wallet_amount, 2) }} {{ $sale->wallet_currency }}
                                @else
                                    —
                                @endif
                            </td>

                            <td class="{{ $pl >= 0 ? 'pl-pos' : 'pl-neg' }}">
                                {{ $pl >= 0 ? '+' : '' }}{{ number_format($pl, 2) }}
                            </td>

                            <td>
                                {{ $sale->created_at->format('d M Y') }}
                                <small>{{ $sale->created_at->format('h:i A') }}</small>
                            </td>

                            <td>
                                <span class="st-badge {{ $sale->status }}">{{ ucfirst($sale->status) }}</span>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="8">
                                <div class="inv-empty">
                                    <div class="icon"><i class="fas fa-chart-line"></i></div>
                                    <h4>No Sales Records Found</h4>
                                    <p>You haven't resold any shares yet. Your resales will appear here.</p>
                                    <a href="{{ route('user.my-shares.index') }}" class="inv-btn primary">
                                        <i class="fas fa-chart-pie"></i> Go to My Shares
                                    </a>
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


    <div class="inv-notice">
        <i class="fas fa-info-circle"></i>
        <div>
            <h5>Sales Record Information</h5>
            <p>
                Profit / Loss is calculated against the amount you originally invested
                in the shares you sold.
            </p>
        </div>
    </div>

</div>

@endsection