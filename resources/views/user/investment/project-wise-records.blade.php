@extends('layouts.app')

@section('title', 'Project-wise Records')

@section('content')

<style>
    .pwr-page {
        display: flex;
        flex-direction: column;
        gap: 22px;
    }

    .pwr-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
    }

    .pwr-heading h2 {
        font-size: 23px;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 6px;
    }

    .pwr-heading p {
        color: #84919f;
        font-size: 13px;
        margin: 0;
    }

    .pwr-date {
        background: #fff;
        border: 1px solid #e8edf0;
        border-radius: 8px;
        padding: 10px 14px;
        color: #64748b;
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .pwr-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
    }

    .pwr-stat-card {
        background: #fff;
        border: 1px solid #e9eef0;
        border-radius: 13px;
        padding: 21px;
        transition: .2s;
    }

    .pwr-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(15,23,42,.05);
    }

    .pwr-stat-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
    }

    .pwr-stat-icon {
        width: 43px;
        height: 43px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .pwr-green { background: #e8f7f0; color: #087f5b; }
    .pwr-blue { background: #edf4ff; color: #3975d7; }
    .pwr-purple { background: #f3edff; color: #805ad5; }
    .pwr-orange { background: #fff3e8; color: #e58b32; }

    .pwr-stat-label {
        color: #84919f;
        font-size: 12px;
        margin-bottom: 8px;
    }

    .pwr-stat-value {
        font-size: 25px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }

    .pwr-panel {
        background: #fff;
        border: 1px solid #e9eef0;
        border-radius: 13px;
        overflow: hidden;
    }

    .pwr-panel-header {
        padding: 21px 22px;
        border-bottom: 1px solid #edf0f2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .pwr-panel-header h3 {
        font-size: 16px;
        color: #1e293b;
        font-weight: 700;
        margin: 0 0 5px;
    }

    .pwr-panel-header p {
        font-size: 12px;
        color: #84919f;
        margin: 0;
    }

    .pwr-filter {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .pwr-filter select {
        padding: 10px 13px;
        border: 1px solid #e8edf0;
        border-radius: 8px;
        color: #475569;
        font-size: 12px;
        background: #fff;
        min-width: 155px;
        outline: none;
    }

    .pwr-filter select:focus {
        border-color: #087f5b;
    }

    .pwr-table-wrap {
        overflow-x: auto;
    }

    .pwr-table {
        width: 100%;
        min-width: 950px;
        border-collapse: collapse;
    }

    .pwr-table th,
    .pwr-table td {
        padding: 15px 18px;
        border-bottom: 1px solid #f0f2f4;
        text-align: left;
        font-size: 12px;
    }

    .pwr-table th {
        background: #f9fafb;
        color: #84919f;
        font-weight: 600;
        white-space: nowrap;
    }

    .pwr-table td {
        color: #475569;
    }

    .pwr-project-name {
        display: flex;
        align-items: center;
        gap: 11px;
        min-width: 190px;
    }

    .pwr-project-icon {
        width: 38px;
        height: 38px;
        border-radius: 9px;
        background: #e8f7f0;
        color: #087f5b;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .pwr-project-name strong {
        display: block;
        color: #1e293b;
        font-size: 12px;
        margin-bottom: 4px;
    }

    .pwr-project-name small {
        color: #94a3b8;
        font-size: 10px;
    }

    .pwr-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 600;
        white-space: nowrap;
    }

    .pwr-status.active {
        color: #087f5b;
        background: #e8f7f0;
    }

    .pwr-status.pending {
        color: #b7791f;
        background: #fff6e8;
    }

    .pwr-status.completed {
        color: #3975d7;
        background: #edf4ff;
    }

    .pwr-status.rejected {
        color: #dc3545;
        background: #fff0f1;
    }

    .pwr-empty {
        text-align: center;
        padding: 55px 20px;
    }

    .pwr-empty-icon {
        width: 68px;
        height: 68px;
        border-radius: 50%;
        background: #f0f7f4;
        color: #087f5b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        margin: 0 auto 17px;
    }

    .pwr-empty h4 {
        font-size: 16px;
        color: #1e293b;
        margin-bottom: 8px;
        font-weight: 700;
    }

    .pwr-empty p {
        max-width: 430px;
        margin: auto;
        font-size: 12px;
        color: #84919f;
        line-height: 1.8;
    }

    .pwr-notice {
        background: #f0f7ff;
        border: 1px solid #d8e8ff;
        border-radius: 11px;
        padding: 17px 19px;
        display: flex;
        align-items: flex-start;
        gap: 13px;
    }

    .pwr-notice > i {
        color: #3975d7;
        font-size: 18px;
        margin-top: 2px;
    }

    .pwr-notice h4 {
        color: #245a9b;
        font-size: 13px;
        margin: 0 0 5px;
        font-weight: 700;
    }

    .pwr-notice p {
        color: #53769d;
        font-size: 12px;
        line-height: 1.8;
        margin: 0;
    }

    @media(max-width: 1050px) {
        .pwr-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media(max-width: 600px) {
        .pwr-panel-header {
            align-items: flex-start;
            padding: 17px;
        }

        .pwr-filter,
        .pwr-filter select {
            width: 100%;
        }

        .pwr-stats {
            gap: 12px;
        }

        .pwr-stat-card {
            padding: 16px;
        }

        .pwr-stat-value {
            font-size: 22px;
        }
    }

    @media(max-width: 380px) {
        .pwr-stats {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="pwr-page">

    {{-- Page Header --}}
    <div class="pwr-heading">

        <div>
            <h2>Project-wise Records</h2>

            <p>
                Track your investment records separately for each project.
            </p>
        </div>

        <div class="pwr-date">
            <i class="fa-regular fa-calendar"></i>
            {{ now()->format('d M, Y') }}
        </div>

    </div>

    {{-- Statistics --}}
    <div class="pwr-stats">

        <div class="pwr-stat-card">

            <div class="pwr-stat-top">
                <div class="pwr-stat-icon pwr-green">
                    <i class="fa-solid fa-building"></i>
                </div>
            </div>

            <div class="pwr-stat-label">Total Projects</div>

            <h3 class="pwr-stat-value">
                {{ number_format($summary['total_projects']) }}
            </h3>

        </div>

        <div class="pwr-stat-card">

            <div class="pwr-stat-top">
                <div class="pwr-stat-icon pwr-blue">
                    <i class="fa-solid fa-cubes"></i>
                </div>
            </div>

            <div class="pwr-stat-label">Total Shares</div>

            <h3 class="pwr-stat-value">
                {{ number_format($summary['total_shares']) }}
            </h3>

        </div>

        <div class="pwr-stat-card">

            <div class="pwr-stat-top">
                <div class="pwr-stat-icon pwr-purple">
                    <i class="fa-solid fa-wallet"></i>
                </div>
            </div>

            <div class="pwr-stat-label">Recorded Amount</div>

            <h3 class="pwr-stat-value">
                {{ number_format($summary['total_amount'], 2) }}
            </h3>

        </div>

        <div class="pwr-stat-card">

            <div class="pwr-stat-top">
                <div class="pwr-stat-icon pwr-orange">
                    <i class="fa-solid fa-folder-open"></i>
                </div>
            </div>

            <div class="pwr-stat-label">Active Projects</div>

            <h3 class="pwr-stat-value">
                {{ number_format($summary['active_projects']) }}
            </h3>

        </div>

    </div>

    {{-- Project Status --}}
    <div class="pwr-stats">

        <div class="pwr-stat-card">
            <div class="pwr-stat-label">Pending Projects</div>
            <h3 class="pwr-stat-value">
                {{ number_format($summary['pending_projects']) }}
            </h3>
        </div>

        <div class="pwr-stat-card">
            <div class="pwr-stat-label">Completed Projects</div>
            <h3 class="pwr-stat-value">
                {{ number_format($summary['completed_projects']) }}
            </h3>
        </div>

    </div>

    {{-- Records Table --}}
    <div class="pwr-panel">

        <div class="pwr-panel-header">

            <div>
                <h3>Project Investment Records</h3>

                <p>
                    All project-wise records associated with your account.
                </p>
            </div>

            <form method="GET"
                  action="{{ route('user.project-wise-records.index') }}"
                  class="pwr-filter">

                <select name="status" onchange="this.form.submit()">

                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>
                        All Status
                    </option>

                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>
                        Pending
                    </option>

                    <option value="active" {{ $status === 'active' ? 'selected' : '' }}>
                        Active
                    </option>

                    <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>
                        Completed
                    </option>

                    <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>
                        Rejected
                    </option>

                </select>

            </form>

        </div>

        <div class="pwr-table-wrap">

            <table class="pwr-table">

                <thead>
                    <tr>
                        <th>Project</th>
                        <th>Project Category</th>
                        <th>Investment Date</th>
                        <th>Share Quantity</th>
                        <th>Recorded Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($projects as $project)

                        <tr>

                            <td>
                                <div class="pwr-project-name">

                                    <div class="pwr-project-icon">
                                        <i class="fa-solid fa-building"></i>
                                    </div>

                                    <div>
                                        <strong>{{ $project->name }}</strong>

                                        <small>
                                            {{ $project->reference ?? '—' }}
                                        </small>
                                    </div>

                                </div>
                            </td>

                            <td>{{ $project->category ?? '—' }}</td>

                            <td>{{ $project->investment_date ?? '—' }}</td>

                            <td>{{ number_format($project->quantity ?? 0) }}</td>

                            <td>
                                {{ number_format($project->amount ?? 0, 2) }}
                            </td>

                            <td>
                                <span class="pwr-status {{ $project->status }}">
                                    {{ ucfirst($project->status) }}
                                </span>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6">

                                <div class="pwr-empty">

                                    <div class="pwr-empty-icon">
                                        <i class="fa-solid fa-folder-open"></i>
                                    </div>

                                    <h4>No Project Records Found</h4>

                                    <p>
                                        No verified project-wise investment
                                        records are currently available for
                                        your account. Records will appear here
                                        when the relevant functionality is
                                        approved and enabled.
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
    <div class="pwr-notice">

        <i class="fa-solid fa-circle-info"></i>

        <div>

            <h4>Investment Data Information</h4>

            <p>
                This page is currently a display-only interface.
                No actual share ownership, investment balance or financial
                return is recorded here. Future project-wise records will
                depend on a verified business model, applicable regulatory
                approvals and secure transaction records.
            </p>

        </div>

    </div>

</div>

@endsection