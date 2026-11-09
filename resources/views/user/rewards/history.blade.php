@extends('layouts.app')

@section('content')

<style>
    .reward-history-page {
        padding: 5px 0 25px;
    }

    .reward-history-page .page-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 25px;
    }

    .reward-history-page .page-heading h2 {
        font-size: 23px;
        font-weight: 700;
        color: var(--text);
        margin: 0 0 6px;
    }

    .reward-history-page .page-heading p {
        color: var(--muted);
        font-size: 13px;
        margin: 0;
    }

    .reward-history-page .page-badge {
        background: #fff7e6;
        color: #b7791f;
        padding: 9px 15px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
    }

    .reward-history-page .summary-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 25px;
    }

    .reward-history-page .summary-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .reward-history-page .summary-icon {
        width: 45px;
        height: 45px;
        min-width: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .reward-history-page .icon-gold {
        background: #fff4d8;
        color: #d69e00;
    }

    .reward-history-page .icon-orange {
        background: #fff3e8;
        color: #ea580c;
    }

    .reward-history-page .icon-green {
        background: #e8f8ef;
        color: #16a34a;
    }

    .reward-history-page .icon-red {
        background: #feecec;
        color: #dc2626;
    }

    .reward-history-page .summary-card h3 {
        font-size: 22px;
        font-weight: 700;
        margin: 0 0 4px;
        color: var(--text);
    }

    .reward-history-page .summary-card p {
        font-size: 12px;
        color: var(--muted);
        margin: 0;
    }

    .reward-history-page .section-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 12px;
        margin-bottom: 22px;
        overflow: hidden;
    }

    .reward-history-page .section-header {
        padding: 19px 22px;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .reward-history-page .section-header h3 {
        font-size: 16px;
        font-weight: 700;
        color: var(--text);
        margin: 0;
    }

    .reward-history-page .filter-form {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .reward-history-page .filter-form select {
        min-width: 145px;
        height: 38px;
        border: 1px solid var(--border);
        border-radius: 7px;
        padding: 0 10px;
        color: var(--text);
        font-size: 12px;
        background: #fff;
        outline: none;
    }

    .reward-history-page .filter-form button {
        height: 38px;
        border: none;
        border-radius: 7px;
        background: var(--primary);
        color: #fff;
        padding: 0 15px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
    }

    .reward-history-page .table-responsive {
        overflow-x: auto;
    }

    .reward-history-page table {
        width: 100%;
        border-collapse: collapse;
        min-width: 750px;
    }

    .reward-history-page table th {
        background: #f8fafc;
        color: #64748b;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        padding: 14px 18px;
        text-align: left;
        white-space: nowrap;
        border-bottom: 1px solid var(--border);
    }

    .reward-history-page table td {
        padding: 15px 18px;
        border-bottom: 1px solid var(--border);
        font-size: 12px;
        color: var(--text);
        white-space: nowrap;
    }

    .reward-history-page .status-badge {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
    }

    .reward-history-page .status-pending {
        background: #fff3e0;
        color: #c77700;
    }

    .reward-history-page .status-approved {
        background: #e8f5ef;
        color: #087f5b;
    }

    .reward-history-page .status-rejected {
        background: #feecec;
        color: #dc2626;
    }

    .reward-history-page .empty-state {
        padding: 55px 20px;
        text-align: center;
    }

    .reward-history-page .empty-icon {
        width: 65px;
        height: 65px;
        border-radius: 50%;
        background: #fff7e6;
        color: #d69e00;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 25px;
        margin: 0 auto 15px;
    }

    .reward-history-page .empty-state h4 {
        font-size: 15px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 7px;
    }

    .reward-history-page .empty-state p {
        font-size: 12px;
        color: var(--muted);
        margin: 0;
    }

    .reward-history-page .notice-box {
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-radius: 10px;
        padding: 17px 20px;
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }

    .reward-history-page .notice-box i {
        color: #d97706;
        font-size: 17px;
        margin-top: 2px;
    }

    .reward-history-page .notice-box h4 {
        color: #92400e;
        font-size: 13px;
        font-weight: 700;
        margin: 0 0 5px;
    }

    .reward-history-page .notice-box p {
        color: #92400e;
        font-size: 11px;
        line-height: 1.7;
        margin: 0;
    }

    @media(max-width: 1000px) {
        .reward-history-page .summary-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media(max-width: 768px) {
        .reward-history-page .filter-form {
            width: 100%;
        }

        .reward-history-page .filter-form select {
            flex: 1;
            min-width: 0;
        }
    }

    @media(max-width: 480px) {
        .reward-history-page .summary-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="reward-history-page">

    {{-- Heading --}}
    <div class="page-heading">
        <div>
            <h2>Reward History</h2>
            <p>View your previous reward records and their current status.</p>
        </div>

        <div class="page-badge">
            <i class="fa-solid fa-clock-rotate-left"></i>
            Reward Records
        </div>
    </div>

    {{-- Summary --}}
    <div class="summary-grid">

        <div class="summary-card">
            <div class="summary-icon icon-gold">
                <i class="fa-solid fa-gift"></i>
            </div>
            <div>
                <h3>{{ $summary['total'] }}</h3>
                <p>Total Records</p>
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-icon icon-orange">
                <i class="fa-solid fa-clock"></i>
            </div>
            <div>
                <h3>{{ $summary['pending'] }}</h3>
                <p>Pending</p>
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-icon icon-green">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <h3>{{ $summary['approved'] }}</h3>
                <p>Approved</p>
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-icon icon-red">
                <i class="fa-solid fa-circle-xmark"></i>
            </div>
            <div>
                <h3>{{ $summary['rejected'] }}</h3>
                <p>Rejected</p>
            </div>
        </div>

    </div>

    {{-- History Table --}}
    <div class="section-card">

        <div class="section-header">

            <h3>
                <i class="fa-solid fa-list"></i>
                All Reward Records
            </h3>

            <form method="GET"
                  action="{{ route('user.reward-history.index') }}"
                  class="filter-form">

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

                    <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>
                        Rejected
                    </option>
                </select>

                <button type="submit">
                    <i class="fa-solid fa-filter"></i>
                    Filter
                </button>

            </form>

        </div>

        <div class="table-responsive">

            <table>

                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Reward ID</th>
                        <th>Reward Type</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($rewards as $index => $reward)

                        <tr>
                            <td>{{ $index + 1 }}</td>

                            <td>{{ $reward->reference_id }}</td>

                            <td>{{ $reward->type }}</td>

                            <td>{{ $reward->description }}</td>

                            <td>
                                {{ $reward->created_at?->format('d M Y') ?? 'N/A' }}
                            </td>

                            <td>
                                <span class="status-badge status-{{ $reward->status }}">
                                    {{ ucfirst($reward->status) }}
                                </span>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="6">

                                <div class="empty-state">

                                    <div class="empty-icon">
                                        <i class="fa-solid fa-gift"></i>
                                    </div>

                                    <h4>No Reward History Found</h4>

                                    <p>
                                        Your reward records will appear here
                                        when an approved reward system is configured.
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
    <div class="notice-box">

        <i class="fa-solid fa-shield-halved"></i>

        <div>
            <h4>Reward History Information</h4>

            <p>
                This page currently displays no actual reward transactions.
                Future records will be shown only after the reward policy,
                eligibility criteria and funding sources are verified.
                No cash bonus or commission is currently enabled.
            </p>
        </div>

    </div>

</div>

@endsection