@extends('layouts.app')

@section('title', 'Purchase History')

@section('content')

<style>
    .purchase-page {
        padding: 5px 0 25px;
    }

    .purchase-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 25px;
    }

    .purchase-heading h2 {
        font-size: 23px;
        font-weight: 700;
        color: var(--text);
        margin: 0 0 5px;
    }

    .purchase-heading p {
        color: var(--muted);
        font-size: 13px;
        margin: 0;
    }

    .page-badge {
        background: #e8f5ef;
        color: var(--primary);
        padding: 9px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
    }

    .purchase-summary {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 25px;
    }

    .summary-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 20px;
    }

    .summary-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        background: #e8f5ef;
        color: var(--primary);
        font-size: 17px;
        margin-bottom: 14px;
    }

    .summary-card .label {
        color: var(--muted);
        font-size: 12px;
        margin-bottom: 8px;
    }

    .summary-card h3 {
        color: var(--text);
        font-size: 25px;
        font-weight: 700;
        margin: 0;
    }

    .summary-card small {
        display: block;
        color: var(--muted);
        font-size: 11px;
        margin-top: 7px;
    }

    .purchase-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
    }

    .purchase-card-header {
        padding: 20px;
        border-bottom: 1px solid var(--border);
    }

    .purchase-card-header h4 {
        font-size: 16px;
        font-weight: 700;
        color: var(--text);
        margin: 0 0 5px;
    }

    .purchase-card-header p {
        font-size: 12px;
        color: var(--muted);
        margin: 0;
    }

    .purchase-filter {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        padding: 18px 20px;
        background: #fafcfc;
        border-bottom: 1px solid var(--border);
    }

    .purchase-filter select {
        min-width: 180px;
        padding: 10px 13px;
        border: 1px solid var(--border);
        border-radius: 8px;
        background: white;
        color: var(--text);
        font-size: 13px;
        outline: none;
    }

    .filter-btn {
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }

    .filter-btn:hover {
        background: var(--primary-dark);
    }

    .reset-btn {
        display: inline-flex;
        align-items: center;
        padding: 10px 16px;
        border: 1px solid var(--border);
        border-radius: 8px;
        color: var(--text);
        text-decoration: none;
        font-size: 13px;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .purchase-table {
        width: 100%;
        min-width: 800px;
        border-collapse: collapse;
    }

    .purchase-table th {
        background: #f8fafb;
        color: var(--muted);
        font-size: 11px;
        font-weight: 600;
        padding: 15px 18px;
        text-align: left;
        white-space: nowrap;
    }

    .purchase-table td {
        padding: 16px 18px;
        border-top: 1px solid var(--border);
        font-size: 12px;
        color: var(--text);
    }

    .empty-state {
        text-align: center;
        padding: 55px 20px;
    }

    .empty-icon {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: #e8f5ef;
        color: var(--primary);
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 28px;
        margin: 0 auto 17px;
    }

    .empty-state h4 {
        font-size: 17px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 8px;
    }

    .empty-state p {
        color: var(--muted);
        font-size: 12px;
        line-height: 1.7;
        max-width: 400px;
        margin: auto;
    }

    .purchase-notice {
        margin-top: 20px;
        padding: 17px 20px;
        border: 1px solid #dcecff;
        background: #f0f7ff;
        border-radius: 10px;
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }

    .purchase-notice i {
        color: #2563eb;
        font-size: 18px;
        margin-top: 2px;
    }

    .purchase-notice h5 {
        color: #1e40af;
        font-size: 13px;
        font-weight: 700;
        margin: 0 0 5px;
    }

    .purchase-notice p {
        color: #475569;
        font-size: 12px;
        line-height: 1.7;
        margin: 0;
    }

    @media(max-width: 1000px) {
        .purchase-summary {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media(max-width: 576px) {
        .purchase-summary {
            gap: 10px;
        }

        .summary-card {
            padding: 15px;
        }

        .summary-card h3 {
            font-size: 21px;
        }

        .purchase-filter select,
        .filter-btn,
        .reset-btn {
            width: 100%;
        }
    }
</style>

<div class="purchase-page">

    {{-- Heading --}}
    <div class="purchase-heading">
        <div>
            <h2>Purchase History</h2>
            <p>View your share-related purchase records.</p>
        </div>

        <span class="page-badge">
            <i class="fas fa-shopping-bag"></i>
            Purchase Records
        </span>
    </div>


    {{-- Summary --}}
    <div class="purchase-summary">

        <div class="summary-card">
            <div class="summary-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>

            <div class="label">Total Records</div>
            <h3>{{ number_format($summary['total_records']) }}</h3>
            <small>All purchase records</small>
        </div>

        <div class="summary-card">
            <div class="summary-icon">
                <i class="fas fa-clock"></i>
            </div>

            <div class="label">Pending</div>
            <h3>{{ number_format($summary['pending']) }}</h3>
            <small>Awaiting review</small>
        </div>

        <div class="summary-card">
            <div class="summary-icon">
                <i class="fas fa-check-circle"></i>
            </div>

            <div class="label">Approved / Completed</div>
            <h3>{{ number_format($summary['approved'] + $summary['completed']) }}</h3>
            <small>Approved and completed records</small>
        </div>

        <div class="summary-card">
            <div class="summary-icon">
                <i class="fas fa-times-circle"></i>
            </div>

            <div class="label">Rejected</div>
            <h3>{{ number_format($summary['rejected']) }}</h3>
            <small>Rejected records</small>
        </div>

    </div>


    {{-- Purchase Records --}}
    <div class="purchase-card">

        <div class="purchase-card-header">
            <h4>All Purchase Records</h4>
            <p>Your recorded purchase information will appear here.</p>
        </div>


        {{-- Filter --}}
        <form method="GET"
              action="{{ route('user.purchase-history.index') }}"
              class="purchase-filter">

            <select name="status">
                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>
                    All Status
                </option>

                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>
                    Pending
                </option>

                <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>
                    Approved
                </option>

                <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>
                    Completed
                </option>

                <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>
                    Rejected
                </option>
            </select>

            <button type="submit" class="filter-btn">
                <i class="fas fa-filter"></i>
                Apply Filter
            </button>

            <a href="{{ route('user.purchase-history.index') }}"
               class="reset-btn">
                Reset
            </a>

        </form>


        {{-- Table --}}
        <div class="table-responsive">

            <table class="purchase-table">

                <thead>
                    <tr>
                        <th>Purchase ID</th>
                        <th>Project Name</th>
                        <th>Reference</th>
                        <th>Purchase Date</th>
                        <th>Quantity</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($purchases as $purchase)

                        <tr>
                            <td>{{ $purchase->id }}</td>
                            <td>{{ $purchase->project_name }}</td>
                            <td>{{ $purchase->reference }}</td>
                            <td>{{ $purchase->created_at->format('d M Y') }}</td>
                            <td>{{ $purchase->quantity }}</td>
                            <td>{{ ucfirst($purchase->status) }}</td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="6">

                                <div class="empty-state">

                                    <div class="empty-icon">
                                        <i class="fas fa-folder-open"></i>
                                    </div>

                                    <h4>No Purchase Records Found</h4>

                                    <p>
                                        You don't have any purchase records yet.
                                        Verified records will appear here when
                                        the relevant system is legally approved
                                        and activated.
                                    </p>

                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- Notice --}}
    <div class="purchase-notice">

        <i class="fas fa-shield-alt"></i>

        <div>
            <h5>Purchase Record Information</h5>

            <p>
                This page is currently informational only.
                No actual share purchases or financial transactions
                can be initiated here. Any future share offering
                requires verified ownership records and applicable
                legal and regulatory approvals.
            </p>
        </div>

    </div>

</div>

@endsection