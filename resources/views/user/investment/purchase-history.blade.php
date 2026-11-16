@extends('layouts.app')

@section('title', 'Purchase History')

@section('content')

@include('partials.styles')

<div class="inv-page">

    {{-- Heading --}}
    <div class="inv-heading">
        <div>
            <h2>Purchase History</h2>
            <p>View all your share purchase records.</p>
        </div>

        <span class="inv-badge"><i class="fas fa-shopping-bag"></i> Purchase Records</span>
    </div>

    @if(session('success'))
        <div class="inv-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="inv-alert error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
    @endif


    {{-- Summary --}}
    <div class="inv-summary">

        <div class="inv-stat">
            <div class="icon"><i class="fas fa-shopping-cart"></i></div>
            <div class="label">Total Purchases</div>
            <h3>{{ number_format($summary['total_records']) }}</h3>
            <small>All purchase records</small>
        </div>

        <div class="inv-stat">
            <div class="icon"><i class="fas fa-layer-group"></i></div>
            <div class="label">Total Shares Bought</div>
            <h3>{{ number_format($summary['total_shares']) }}</h3>
            <small>Across all projects</small>
        </div>

        <div class="inv-stat">
            <div class="icon"><i class="fas fa-coins"></i></div>
            <div class="label">Total Spent (BDT)</div>
            <h3>{{ number_format((float) $summary['total_spent_bdt'], 2) }}</h3>
            <small>Net amount</small>
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
            <h4>All Purchase Records</h4>
            <p>Your share purchases, newest first.</p>
        </div>

        <form method="GET" action="{{ route('user.purchase-history.index') }}" class="inv-filter">

            <select name="status">
                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Status</option>
                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>

            <button type="submit" class="inv-btn primary"><i class="fas fa-filter"></i> Apply Filter</button>

            <a href="{{ route('user.purchase-history.index') }}" class="inv-btn outline">Reset</a>

        </form>

        <div class="inv-table-wrap">

            <table class="inv-table">

                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Project</th>
                        <th>Shares</th>
                        <th>Net Amount (BDT)</th>
                        <th>Paid From Wallet</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($purchases as $purchase)

                        <tr>
                            <td>#{{ $purchase->id }}</td>

                            <td>
                                @if($purchase->project)
                                    <a href="{{ route('user.project-details.show', $purchase->project->slug) }}"
                                       style="color:var(--text); font-weight:600; text-decoration:none;">
                                        {{ $purchase->project->name }}
                                    </a>
                                    <small>{{ $purchase->project->company->name ?? 'N/A' }}</small>
                                @else
                                    <span style="color:var(--muted);">Project removed</span>
                                @endif
                            </td>

                            <td><strong>{{ number_format($purchase->shares) }}</strong></td>

                            <td>{{ number_format((float) $purchase->net_bdt, 2) }}</td>

                            <td>
                                @if($purchase->wallet_amount)
                                    {{ number_format((float) $purchase->wallet_amount, 2) }} {{ $purchase->wallet_currency }}
                                @else
                                    —
                                @endif
                            </td>

                            <td>
                                {{ $purchase->created_at->format('d M Y') }}
                                <small>{{ $purchase->created_at->format('h:i A') }}</small>
                            </td>

                            <td>
                                <span class="st-badge {{ $purchase->status }}">{{ ucfirst($purchase->status) }}</span>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="7">
                                <div class="inv-empty">
                                    <div class="icon"><i class="fas fa-folder-open"></i></div>
                                    <h4>No Purchase Records Found</h4>
                                    <p>You haven't bought any shares yet. Your purchases will appear here.</p>
                                    <a href="{{ route('user.available-projects.index') }}" class="inv-btn primary">
                                        <i class="fas fa-search"></i> Browse Available Projects
                                    </a>
                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if($purchases->hasPages())
            <div class="inv-pagination">{{ $purchases->links() }}</div>
        @endif

    </div>


    <div class="inv-notice">
        <i class="fas fa-info-circle"></i>
        <div>
            <h5>Purchase Record Information</h5>
            <p>
                Each purchase is recorded with the amount charged in BDT and the amount
                deducted from the wallet you paid with.
            </p>
        </div>
    </div>

</div>

@endsection