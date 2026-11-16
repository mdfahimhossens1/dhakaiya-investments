@extends('layouts.app')

@section('title', 'Share Transfer / Resale')

@section('content')

@include('partials.styles')

<style>
    .tr-info { display:grid; grid-template-columns:repeat(auto-fit, minmax(230px, 1fr)); gap:18px; margin-bottom:20px; }
    .tr-info-card { background:var(--white); border:1px solid var(--border); border-radius:12px; padding:22px; }
    .tr-info-card i { font-size:24px; color:var(--primary); margin-bottom:14px; }
    .tr-info-card h4 { margin:0 0 8px; color:var(--text); font-size:15px; }
    .tr-info-card p { color:var(--muted); font-size:13px; line-height:1.7; margin:0; }
</style>

<div class="inv-page">

    {{-- Heading --}}
    <div class="inv-heading">
        <div>
            <h2>Share Transfer / Resale</h2>
            <p>View share transfer and resale information.</p>
        </div>

        <span class="inv-badge"><i class="fas fa-right-left"></i> Transfer Records</span>
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
            <div class="icon"><i class="fas fa-layer-group"></i></div>
            <div class="label">Total Records</div>
            <h3>{{ number_format($summary['total_records']) }}</h3>
            <small>All transfer requests</small>
        </div>

        <div class="inv-stat">
            <div class="icon"><i class="fas fa-clock"></i></div>
            <div class="label">Pending Requests</div>
            <h3>{{ number_format($summary['pending']) }}</h3>
            <small>Awaiting review</small>
        </div>

        <div class="inv-stat">
            <div class="icon"><i class="fas fa-check-circle"></i></div>
            <div class="label">Completed Transfers</div>
            <h3>{{ number_format($summary['completed']) }}</h3>
            <small>{{ number_format($summary['approved']) }} approved</small>
        </div>

        <div class="inv-stat">
            <div class="icon"><i class="fas fa-times-circle"></i></div>
            <div class="label">Rejected Requests</div>
            <h3>{{ number_format($summary['rejected']) }}</h3>
            <small>Rejected records</small>
        </div>

    </div>


    {{-- Information Cards --}}
    <div class="tr-info">

        <div class="tr-info-card">
            <i class="fas fa-right-left"></i>
            <h4>Share Transfer</h4>
            <p>Transferring ownership between eligible users, subject to applicable rules and verification.</p>
        </div>

        <div class="tr-info-card">
            <i class="fas fa-hand-holding-usd"></i>
            <h4>Share Resale</h4>
            <p>
                You can already resell your shares from the
                <a href="{{ route('user.my-shares.index') }}" style="color:var(--primary); font-weight:600;">My Shares</a>
                page. Resale history is available in
                <a href="{{ route('user.sales-history.index') }}" style="color:var(--primary); font-weight:600;">Sales History</a>.
            </p>
        </div>

        <div class="tr-info-card">
            <i class="fas fa-shield-alt"></i>
            <h4>Security & Verification</h4>
            <p>Any future transfer process will require identity checks, ownership verification and security controls.</p>
        </div>

    </div>


    {{-- History --}}
    <div class="inv-panel">

        <div class="inv-panel-header">
            <h4>Transfer History</h4>
            <p>Review your previous share transfer records.</p>
        </div>

        <form method="GET" action="{{ route('user.share-transfer.index') }}" class="inv-filter">

            <select name="status">
                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Status</option>
                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>

            <button type="submit" class="inv-btn primary"><i class="fas fa-filter"></i> Apply Filter</button>

            <a href="{{ route('user.share-transfer.index') }}" class="inv-btn outline">Reset</a>

        </form>

        <div class="inv-table-wrap">

            <table class="inv-table">

                <thead>
                    <tr>
                        <th>Request ID</th>
                        <th>Type</th>
                        <th>Project</th>
                        <th>Shares</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($transfers as $transfer)

                        <tr>
                            <td>#{{ $transfer->id }}</td>
                            <td>{{ ucfirst($transfer->type ?? 'transfer') }}</td>
                            <td>{{ $transfer->project->name ?? '—' }}</td>
                            <td>{{ number_format($transfer->shares ?? 0) }}</td>
                            <td>{{ optional($transfer->created_at)->format('d M Y') }}</td>
                            <td>
                                <span class="st-badge {{ $transfer->status }}">{{ ucfirst($transfer->status) }}</span>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="6">
                                <div class="inv-empty">
                                    <div class="icon"><i class="fas fa-right-left"></i></div>
                                    <h4>No Transfer Records Found</h4>
                                    <p>You don't have any share transfer records yet.</p>
                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    <div class="inv-notice">
        <i class="fas fa-info-circle"></i>
        <div>
            <h5>Important Notice</h5>
            <p>
                User-to-user share transfer is not active yet. No transfer or ownership change
                can be initiated from this page.
            </p>
        </div>
    </div>

</div>

@endsection