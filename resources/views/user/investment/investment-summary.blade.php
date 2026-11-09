@extends('layouts.app')

@section('title', 'Investment Summary')

@section('content')

<style>
    .investment-page {
        display: flex;
        flex-direction: column;
        gap: 22px;
    }

    .investment-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .investment-heading h2 {
        font-size: 23px;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 6px;
    }

    .investment-heading p {
        font-size: 13px;
        color: #84919f;
        margin: 0;
    }

    .investment-date {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #fff;
        border: 1px solid #e8edf0;
        border-radius: 8px;
        padding: 10px 14px;
        color: #64748b;
        font-size: 12px;
    }

    .investment-overview {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
    }

    .investment-card {
        background: #fff;
        border: 1px solid #e9eef0;
        border-radius: 13px;
        padding: 21px;
        transition: 0.2s ease;
        position: relative;
        overflow: hidden;
    }

    .investment-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.05);
    }

    .investment-card-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
    }

    .investment-icon {
        width: 43px;
        height: 43px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .icon-green {
        background: #e8f7f0;
        color: #087f5b;
    }

    .icon-blue {
        background: #edf4ff;
        color: #3975d7;
    }

    .icon-purple {
        background: #f3edff;
        color: #805ad5;
    }

    .icon-orange {
        background: #fff3e8;
        color: #e58b32;
    }

    .investment-card-label {
        font-size: 12px;
        color: #84919f;
        font-weight: 500;
        margin-bottom: 8px;
    }

    .investment-card-value {
        font-size: 25px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }

    .investment-card-footer {
        font-size: 11px;
        color: #94a3b8;
        margin-top: 9px;
    }

    .investment-secondary {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
    }

    .investment-status-card {
        background: #fff;
        border: 1px solid #e9eef0;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .status-icon {
        width: 43px;
        height: 43px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
        flex-shrink: 0;
    }

    .status-icon.active {
        background: #e8f7f0;
        color: #087f5b;
    }

    .status-icon.pending {
        background: #fff6e8;
        color: #e49a27;
    }

    .status-icon.completed {
        background: #edf4ff;
        color: #3975d7;
    }

    .status-info span {
        display: block;
        font-size: 12px;
        color: #84919f;
        margin-bottom: 5px;
    }

    .status-info h3 {
        margin: 0;
        font-size: 21px;
        color: #1e293b;
        font-weight: 700;
    }

    .investment-panel {
        background: #fff;
        border: 1px solid #e9eef0;
        border-radius: 13px;
        overflow: hidden;
    }

    .investment-panel-header {
        padding: 21px 22px;
        border-bottom: 1px solid #edf0f2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .investment-panel-header h3 {
        margin: 0 0 5px;
        font-size: 16px;
        color: #1e293b;
        font-weight: 700;
    }

    .investment-panel-header p {
        margin: 0;
        color: #84919f;
        font-size: 12px;
    }

    .investment-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 850px;
    }

    .investment-table th,
    .investment-table td {
        padding: 15px 18px;
        text-align: left;
        border-bottom: 1px solid #f0f2f4;
        font-size: 12px;
    }

    .investment-table th {
        background: #f9fafb;
        color: #84919f;
        font-weight: 600;
        white-space: nowrap;
    }

    .investment-table td {
        color: #475569;
    }

    .investment-empty {
        text-align: center;
        padding: 55px 20px;
    }

    .investment-empty-icon {
        width: 68px;
        height: 68px;
        background: #f0f7f4;
        color: #087f5b;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 17px;
        font-size: 26px;
    }

    .investment-empty h4 {
        color: #1e293b;
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .investment-empty p {
        max-width: 420px;
        margin: auto;
        color: #84919f;
        font-size: 12px;
        line-height: 1.8;
    }

    .investment-breakdown {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }

    .breakdown-content {
        padding: 22px;
    }

    .breakdown-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 0;
        border-bottom: 1px solid #f0f2f4;
        font-size: 12px;
    }

    .breakdown-item:last-child {
        border-bottom: none;
    }

    .breakdown-label {
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .breakdown-value {
        font-weight: 700;
        color: #1e293b;
    }

    .investment-notice {
        background: #f0f7ff;
        border: 1px solid #d8e8ff;
        border-radius: 11px;
        padding: 17px 19px;
        display: flex;
        gap: 13px;
        align-items: flex-start;
    }

    .investment-notice > i {
        color: #3975d7;
        font-size: 18px;
        margin-top: 2px;
    }

    .investment-notice h4 {
        font-size: 13px;
        font-weight: 700;
        color: #245a9b;
        margin: 0 0 5px;
    }

    .investment-notice p {
        font-size: 12px;
        line-height: 1.8;
        color: #53769d;
        margin: 0;
    }

    @media(max-width: 1100px) {
        .investment-overview {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media(max-width: 700px) {
        .investment-secondary {
            grid-template-columns: 1fr;
        }

        .investment-breakdown {
            grid-template-columns: 1fr;
        }
    }

    @media(max-width: 480px) {
        .investment-overview {
            grid-template-columns: 1fr;
        }

        .investment-card {
            padding: 18px;
        }

        .investment-card-value {
            font-size: 23px;
        }

        .investment-panel-header {
            padding: 17px;
        }
    }
</style>

<div class="investment-page">

    {{-- Page Heading --}}
    <div class="investment-heading">

        <div>
            <h2>Investment Summary</h2>

            <p>
                Overview of your investment portfolio and activity.
            </p>
        </div>

        <div class="investment-date">
            <i class="fa-regular fa-calendar"></i>
            {{ now()->format('d M, Y') }}
        </div>

    </div>

    {{-- Main Overview --}}
    <div class="investment-overview">

        <div class="investment-card">

            <div class="investment-card-top">
                <div class="investment-icon icon-green">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
            </div>

            <div class="investment-card-label">
                Total Projects
            </div>

            <h3 class="investment-card-value">
                {{ number_format($summary['total_projects']) }}
            </h3>

            <div class="investment-card-footer">
                Projects associated with your account
            </div>

        </div>

        <div class="investment-card">

            <div class="investment-card-top">
                <div class="investment-icon icon-blue">
                    <i class="fa-solid fa-cubes"></i>
                </div>
            </div>

            <div class="investment-card-label">
                Total Shares
            </div>

            <h3 class="investment-card-value">
                {{ number_format($summary['total_shares']) }}
            </h3>

            <div class="investment-card-footer">
                Recorded share quantity
            </div>

        </div>

        <div class="investment-card">

            <div class="investment-card-top">
                <div class="investment-icon icon-purple">
                    <i class="fa-solid fa-wallet"></i>
                </div>
            </div>

            <div class="investment-card-label">
                Total Invested
            </div>

            <h3 class="investment-card-value">
                {{ number_format($summary['total_invested'], 2) }}
            </h3>

            <div class="investment-card-footer">
                Verified investment records only
            </div>

        </div>

        <div class="investment-card">

            <div class="investment-card-top">
                <div class="investment-icon icon-orange">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
            </div>

            <div class="investment-card-label">
                Total Profit / Loss
            </div>

            <h3 class="investment-card-value">
                {{ number_format($summary['total_profit'], 2) }}
            </h3>

            <div class="investment-card-footer">
                No projected or estimated returns included
            </div>

        </div>

    </div>

    {{-- Investment Status --}}
    <div class="investment-secondary">

        <div class="investment-status-card">

            <div class="status-icon active">
                <i class="fa-solid fa-circle-check"></i>
            </div>

            <div class="status-info">
                <span>Active Investments</span>
                <h3>{{ $summary['active_investments'] }}</h3>
            </div>

        </div>

        <div class="investment-status-card">

            <div class="status-icon pending">
                <i class="fa-solid fa-clock"></i>
            </div>

            <div class="status-info">
                <span>Pending Investments</span>
                <h3>{{ $summary['pending_investments'] }}</h3>
            </div>

        </div>

        <div class="investment-status-card">

            <div class="status-icon completed">
                <i class="fa-solid fa-flag-checkered"></i>
            </div>

            <div class="status-info">
                <span>Completed Investments</span>
                <h3>{{ $summary['completed_investments'] }}</h3>
            </div>

        </div>

    </div>

    {{-- Project Breakdown --}}
    <div class="investment-breakdown">

        <div class="investment-panel">

            <div class="investment-panel-header">
                <div>
                    <h3>Portfolio Breakdown</h3>
                    <p>Investment distribution by project.</p>
                </div>
            </div>

            <div class="breakdown-content">

                @forelse($projectBreakdown as $project)

                    <div class="breakdown-item">

                        <div class="breakdown-label">
                            <i class="fa-solid fa-circle" style="color:#087f5b;"></i>
                            {{ $project->name }}
                        </div>

                        <div class="breakdown-value">
                            {{ number_format($project->amount, 2) }}
                        </div>

                    </div>

                @empty

                    <div class="investment-empty" style="padding:35px 10px;">

                        <div class="investment-empty-icon">
                            <i class="fa-solid fa-chart-pie"></i>
                        </div>

                        <h4>No Portfolio Data</h4>

                        <p>
                            Project-wise portfolio information will appear
                            here when verified records are available.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>

        <div class="investment-panel">

            <div class="investment-panel-header">
                <div>
                    <h3>Investment Activity</h3>
                    <p>Recent activity in your account.</p>
                </div>
            </div>

            <div class="breakdown-content">

                <div class="breakdown-item">
                    <div class="breakdown-label">
                        <i class="fa-solid fa-cart-shopping" style="color:#3975d7;"></i>
                        Purchase Records
                    </div>

                    <div class="breakdown-value">
                        {{ $summary['total_shares'] }}
                    </div>
                </div>

                <div class="breakdown-item">
                    <div class="breakdown-label">
                        <i class="fa-solid fa-clock" style="color:#e49a27;"></i>
                        Pending Records
                    </div>

                    <div class="breakdown-value">
                        {{ $summary['pending_investments'] }}
                    </div>
                </div>

                <div class="breakdown-item">
                    <div class="breakdown-label">
                        <i class="fa-solid fa-circle-check" style="color:#087f5b;"></i>
                        Active Records
                    </div>

                    <div class="breakdown-value">
                        {{ $summary['active_investments'] }}
                    </div>
                </div>

                <div class="breakdown-item">
                    <div class="breakdown-label">
                        <i class="fa-solid fa-flag-checkered" style="color:#3975d7;"></i>
                        Completed Records
                    </div>

                    <div class="breakdown-value">
                        {{ $summary['completed_investments'] }}
                    </div>
                </div>

            </div>

        </div>

    </div>

    {{-- Investment History --}}
    <div class="investment-panel">

        <div class="investment-panel-header">

            <div>
                <h3>Investment Records</h3>

                <p>
                    Complete overview of your recorded investments.
                </p>
            </div>

        </div>

        <div style="overflow-x:auto;">

            <table class="investment-table">

                <thead>
                    <tr>
                        <th>Investment ID</th>
                        <th>Project Name</th>
                        <th>Investment Date</th>
                        <th>Quantity</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($investments as $investment)

                        <tr>
                            <td>#{{ $investment->id }}</td>
                            <td>{{ $investment->project_name }}</td>
                            <td>{{ $investment->investment_date }}</td>
                            <td>{{ $investment->quantity }}</td>
                            <td>{{ number_format($investment->amount, 2) }}</td>
                            <td>{{ ucfirst($investment->status) }}</td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="6">

                                <div class="investment-empty">

                                    <div class="investment-empty-icon">
                                        <i class="fa-solid fa-folder-open"></i>
                                    </div>

                                    <h4>No Investment Records Found</h4>

                                    <p>
                                        There are currently no verified
                                        investment records associated with
                                        your account. Available records will
                                        appear here when the relevant
                                        functionality is approved and enabled.
                                    </p>

                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    {{-- Information Notice --}}
    <div class="investment-notice">

        <i class="fa-solid fa-circle-info"></i>

        <div>

            <h4>Investment Information & Disclaimer</h4>

            <p>
                This page currently displays an interface preview.
                Investment balances, profit/loss figures and project
                allocations are not activated. No financial return,
                investment ownership or profit is represented by the
                placeholder values. Any future financial functionality
                will require a verified business model, applicable
                regulatory approvals and appropriate legal and
                security arrangements.
            </p>

        </div>

    </div>

</div>

@endsection