@extends('layouts.app')

@section('content')

<style>
    .maturity-page {
        padding: 5px 0 25px;
    }

    .maturity-page .page-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 25px;
    }

    .maturity-page .page-heading h2 {
        font-size: 23px;
        font-weight: 700;
        color: var(--text);
        margin: 0 0 6px;
    }

    .maturity-page .page-heading p {
        color: var(--muted);
        font-size: 13px;
        margin: 0;
    }

    .maturity-page .info-badge {
        background: #e8f5ef;
        color: #087f5b;
        padding: 9px 15px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
    }

    .maturity-page .summary-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 16px;
        margin-bottom: 25px;
    }

    .maturity-page .summary-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .maturity-page .summary-icon {
        width: 45px;
        height: 45px;
        min-width: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .maturity-page .icon-blue {
        background: #eaf2ff;
        color: #2563eb;
    }

    .maturity-page .icon-orange {
        background: #fff3e8;
        color: #ea580c;
    }

    .maturity-page .icon-green {
        background: #e8f8ef;
        color: #16a34a;
    }

    .maturity-page .icon-purple {
        background: #f3eaff;
        color: #9333ea;
    }

    .maturity-page .icon-gray {
        background: #f1f5f9;
        color: #64748b;
    }

    .maturity-page .summary-card h3 {
        font-size: 22px;
        font-weight: 700;
        margin: 0 0 4px;
        color: var(--text);
    }

    .maturity-page .summary-card p {
        font-size: 12px;
        color: var(--muted);
        margin: 0;
    }

    .maturity-page .section-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 12px;
        margin-bottom: 22px;
        overflow: hidden;
    }

    .maturity-page .section-header {
        padding: 19px 22px;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .maturity-page .section-header h3 {
        font-size: 16px;
        font-weight: 700;
        color: var(--text);
        margin: 0;
    }

    .maturity-page .section-body {
        padding: 22px;
    }

    .maturity-page .timeline-box {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        padding: 20px;
        border: 1px dashed #cbd5e1;
        border-radius: 10px;
        background: #fafcfd;
    }

    .maturity-page .timeline-icon {
        width: 43px;
        height: 43px;
        min-width: 43px;
        border-radius: 10px;
        background: #eaf2ff;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .maturity-page .timeline-box h4 {
        font-size: 14px;
        font-weight: 700;
        color: var(--text);
        margin: 2px 0 6px;
    }

    .maturity-page .timeline-box p {
        font-size: 12px;
        color: var(--muted);
        line-height: 1.7;
        margin: 0;
    }

    .maturity-page .filter-form {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .maturity-page .filter-form select {
        min-width: 150px;
        height: 38px;
        border: 1px solid var(--border);
        border-radius: 7px;
        padding: 0 10px;
        color: var(--text);
        font-size: 12px;
        background: #fff;
        outline: none;
    }

    .maturity-page .filter-form button {
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

    .maturity-page .table-responsive {
        overflow-x: auto;
    }

    .maturity-page table {
        width: 100%;
        border-collapse: collapse;
        min-width: 850px;
    }

    .maturity-page table th {
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

    .maturity-page table td {
        padding: 15px 18px;
        border-bottom: 1px solid var(--border);
        font-size: 12px;
        color: var(--text);
        white-space: nowrap;
    }

    .maturity-page .status-badge {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
    }

    .maturity-page .status-upcoming {
        background: #fff3e0;
        color: #c77700;
    }

    .maturity-page .status-matured {
        background: #e8f5ef;
        color: #087f5b;
    }

    .maturity-page .status-completed {
        background: #eaf2ff;
        color: #2563eb;
    }

    .maturity-page .status-pending {
        background: #f1f5f9;
        color: #64748b;
    }

    .maturity-page .empty-state {
        padding: 55px 20px;
        text-align: center;
    }

    .maturity-page .empty-icon {
        width: 65px;
        height: 65px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #94a3b8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 25px;
        margin: 0 auto 15px;
    }

    .maturity-page .empty-state h4 {
        font-size: 15px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 7px;
    }

    .maturity-page .empty-state p {
        font-size: 12px;
        color: var(--muted);
        margin: 0;
    }

    .maturity-page .notice-box {
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-radius: 10px;
        padding: 17px 20px;
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }

    .maturity-page .notice-box i {
        color: #d97706;
        font-size: 17px;
        margin-top: 2px;
    }

    .maturity-page .notice-box h4 {
        color: #92400e;
        font-size: 13px;
        font-weight: 700;
        margin: 0 0 5px;
    }

    .maturity-page .notice-box p {
        color: #92400e;
        font-size: 11px;
        line-height: 1.7;
        margin: 0;
    }

    @media(max-width: 1200px) {
        .maturity-page .summary-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media(max-width: 768px) {
        .maturity-page .summary-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .maturity-page .summary-card {
            padding: 14px;
            gap: 10px;
        }

        .maturity-page .summary-icon {
            width: 38px;
            height: 38px;
            min-width: 38px;
            font-size: 15px;
        }

        .maturity-page .summary-card h3 {
            font-size: 19px;
        }

        .maturity-page .section-body {
            padding: 15px;
        }

        .maturity-page .filter-form {
            width: 100%;
        }

        .maturity-page .filter-form select {
            flex: 1;
            min-width: 0;
        }
    }

    @media(max-width: 400px) {
        .maturity-page .summary-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="maturity-page">

    {{-- Page Heading --}}
    <div class="page-heading">
        <div>
            <h2>Maturity Information</h2>
            <p>View your investment maturity dates and status information.</p>
        </div>

        <div class="info-badge">
            <i class="fa-solid fa-calendar-check"></i>
            Maturity Overview
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="summary-grid">

        <div class="summary-card">
            <div class="summary-icon icon-blue">
                <i class="fa-solid fa-folder-open"></i>
            </div>
            <div>
                <h3>{{ $summary['total_records'] }}</h3>
                <p>Total Records</p>
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-icon icon-orange">
                <i class="fa-solid fa-clock"></i>
            </div>
            <div>
                <h3>{{ $summary['upcoming'] }}</h3>
                <p>Upcoming Maturity</p>
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-icon icon-green">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
            <div>
                <h3>{{ $summary['matured'] }}</h3>
                <p>Matured Records</p>
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-icon icon-purple">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <h3>{{ $summary['completed'] }}</h3>
                <p>Completed</p>
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-icon icon-gray">
                <i class="fa-solid fa-hourglass-half"></i>
            </div>
            <div>
                <h3>{{ $summary['pending'] }}</h3>
                <p>Pending</p>
            </div>
        </div>

    </div>

    {{-- Upcoming Maturity --}}
    <div class="section-card">

        <div class="section-header">
            <h3>
                <i class="fa-solid fa-calendar-days"></i>
                Upcoming Maturity
            </h3>
        </div>

        <div class="section-body">

            <div class="timeline-box">
                <div class="timeline-icon">
                    <i class="fa-solid fa-hourglass-start"></i>
                </div>

                <div>
                    <h4>No Upcoming Maturity</h4>
                    <p>
                        You currently have no maturity records.
                        When verified maturity records become available,
                        their relevant dates and status will appear here.
                    </p>
                </div>
            </div>

        </div>

    </div>

    {{-- Maturity History --}}
    <div class="section-card">

        <div class="section-header">

            <h3>
                <i class="fa-solid fa-list-check"></i>
                Maturity Records
            </h3>

            <form method="GET"
                  action="{{ route('user.maturity-information.index') }}"
                  class="filter-form">

                <select name="status">
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>
                        All Status
                    </option>

                    <option value="upcoming" {{ $status === 'upcoming' ? 'selected' : '' }}>
                        Upcoming
                    </option>

                    <option value="matured" {{ $status === 'matured' ? 'selected' : '' }}>
                        Matured
                    </option>

                    <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>
                        Completed
                    </option>

                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>
                        Pending
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
                        <th>Project Name</th>
                        <th>Reference ID</th>
                        <th>Start Date</th>
                        <th>Maturity Date</th>
                        <th>Investment Term</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($maturities as $index => $maturity)

                        <tr>
                            <td>{{ $index + 1 }}</td>

                            <td>
                                {{ $maturity->project_name }}
                            </td>

                            <td>
                                {{ $maturity->reference_id }}
                            </td>

                            <td>
                                {{ $maturity->start_date }}
                            </td>

                            <td>
                                {{ $maturity->maturity_date }}
                            </td>

                            <td>
                                {{ $maturity->term ?? 'N/A' }}
                            </td>

                            <td>
                                <span class="status-badge status-{{ $maturity->status }}">
                                    {{ ucfirst($maturity->status) }}
                                </span>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="7">

                                <div class="empty-state">

                                    <div class="empty-icon">
                                        <i class="fa-solid fa-calendar-xmark"></i>
                                    </div>

                                    <h4>No Maturity Records Found</h4>

                                    <p>
                                        Your maturity information will appear here
                                        when verified records are available.
                                    </p>

                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>
            </table>

        </div>

    </div>

    {{-- Important Notice --}}
    <div class="notice-box">

        <i class="fa-solid fa-shield-halved"></i>

        <div>
            <h4>Important Information</h4>

            <p>
                Maturity dates and terms will only be displayed when they
                are officially defined and verified. A maturity date does
                not guarantee any particular return or payment.
                Any future maturity-related feature will depend on the
                approved business model and applicable legal requirements.
            </p>
        </div>

    </div>

</div>

@endsection