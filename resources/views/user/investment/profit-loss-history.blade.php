@extends('layouts.app')

@section('title', 'Profit & Loss History')

@section('content')

<style>
    .pl-page {
        display: flex;
        flex-direction: column;
        gap: 22px;
    }

    .pl-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
    }

    .pl-heading h2 {
        font-size: 23px;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 6px;
    }

    .pl-heading p {
        font-size: 13px;
        color: #84919f;
        margin: 0;
    }

    .pl-date {
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

    .pl-summary {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
    }

    .pl-card {
        background: #fff;
        border: 1px solid #e9eef0;
        border-radius: 13px;
        padding: 21px;
        transition: .2s ease;
    }

    .pl-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(15,23,42,.05);
    }

    .pl-card-top {
        display: flex;
        justify-content: space-between;
        margin-bottom: 18px;
    }

    .pl-icon {
        width: 43px;
        height: 43px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .pl-icon.green {
        background: #e8f7f0;
        color: #087f5b;
    }

    .pl-icon.red {
        background: #fff0f1;
        color: #dc3545;
    }

    .pl-icon.blue {
        background: #edf4ff;
        color: #3975d7;
    }

    .pl-icon.purple {
        background: #f3edff;
        color: #805ad5;
    }

    .pl-label {
        font-size: 12px;
        color: #84919f;
        margin-bottom: 8px;
    }

    .pl-value {
        font-size: 25px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }

    .pl-card-footer {
        font-size: 11px;
        color: #94a3b8;
        margin-top: 9px;
    }

    .pl-breakdown {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .pl-panel {
        background: #fff;
        border: 1px solid #e9eef0;
        border-radius: 13px;
        overflow: hidden;
    }

    .pl-panel-header {
        padding: 21px 22px;
        border-bottom: 1px solid #edf0f2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .pl-panel-header h3 {
        margin: 0 0 5px;
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
    }

    .pl-panel-header p {
        margin: 0;
        color: #84919f;
        font-size: 12px;
    }

    .pl-breakdown-content {
        padding: 22px;
    }

    .pl-breakdown-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding: 15px 0;
        border-bottom: 1px solid #f0f2f4;
    }

    .pl-breakdown-row:last-child {
        border-bottom: none;
    }

    .pl-breakdown-label {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #64748b;
        font-size: 12px;
    }

    .pl-breakdown-value {
        font-weight: 700;
        font-size: 13px;
        color: #1e293b;
    }

    .pl-dot {
        width: 9px;
        height: 9px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .pl-profit {
        background: #087f5b;
    }

    .pl-loss {
        background: #dc3545;
    }

    .pl-neutral {
        background: #3975d7;
    }

    .pl-table-wrap {
        overflow-x: auto;
    }

    .pl-table {
        width: 100%;
        min-width: 850px;
        border-collapse: collapse;
    }

    .pl-table th,
    .pl-table td {
        padding: 15px 18px;
        text-align: left;
        border-bottom: 1px solid #f0f2f4;
        font-size: 12px;
    }

    .pl-table th {
        background: #f9fafb;
        color: #84919f;
        font-weight: 600;
        white-space: nowrap;
    }

    .pl-table td {
        color: #475569;
    }

    .pl-type {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 600;
    }

    .pl-type.profit {
        background: #e8f7f0;
        color: #087f5b;
    }

    .pl-type.loss {
        background: #fff0f1;
        color: #dc3545;
    }

    .pl-empty {
        text-align: center;
        padding: 55px 20px;
    }

    .pl-empty-icon {
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

    .pl-empty h4 {
        color: #1e293b;
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .pl-empty p {
        max-width: 430px;
        margin: auto;
        font-size: 12px;
        color: #84919f;
        line-height: 1.8;
    }

    .pl-filter {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .pl-filter select {
        padding: 10px 13px;
        border: 1px solid #e8edf0;
        border-radius: 8px;
        background: #fff;
        color: #475569;
        font-size: 12px;
        min-width: 145px;
        outline: none;
    }

    .pl-filter select:focus {
        border-color: #087f5b;
    }

    .pl-notice {
        background: #f0f7ff;
        border: 1px solid #d8e8ff;
        border-radius: 11px;
        padding: 17px 19px;
        display: flex;
        align-items: flex-start;
        gap: 13px;
    }

    .pl-notice > i {
        color: #3975d7;
        font-size: 18px;
        margin-top: 2px;
    }

    .pl-notice h4 {
        color: #245a9b;
        font-size: 13px;
        font-weight: 700;
        margin: 0 0 5px;
    }

    .pl-notice p {
        color: #53769d;
        font-size: 12px;
        line-height: 1.8;
        margin: 0;
    }

    @media(max-width: 1050px) {
        .pl-summary {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media(max-width: 700px) {
        .pl-breakdown {
            grid-template-columns: 1fr;
        }
    }

    @media(max-width: 480px) {
        .pl-summary {
            grid-template-columns: 1fr;
        }

        .pl-card {
            padding: 18px;
        }

        .pl-panel-header {
            padding: 17px;
        }

        .pl-filter,
        .pl-filter select {
            width: 100%;
        }
    }
</style>

<div class="pl-page">

    {{-- Heading --}}
    <div class="pl-heading">

        <div>
            <h2>Profit & Loss History</h2>

            <p>
                Review your recorded financial results and history.
            </p>
        </div>

        <div class="pl-date">
            <i class="fa-regular fa-calendar"></i>
            {{ now()->format('d M, Y') }}
        </div>

    </div>

    {{-- Financial Summary --}}
    <div class="pl-summary">

        <div class="pl-card">

            <div class="pl-card-top">
                <div class="pl-icon green">
                    <i class="fa-solid fa-arrow-trend-up"></i>
                </div>
            </div>

            <div class="pl-label">Total Recorded Profit</div>

            <h3 class="pl-value">
                {{ number_format($summary['total_profit'], 2) }}
            </h3>

            <div class="pl-card-footer">
                Verified profit records only
            </div>

        </div>

        <div class="pl-card">

            <div class="pl-card-top">
                <div class="pl-icon red">
                    <i class="fa-solid fa-arrow-trend-down"></i>
                </div>
            </div>

            <div class="pl-label">Total Recorded Loss</div>

            <h3 class="pl-value">
                {{ number_format($summary['total_loss'], 2) }}
            </h3>

            <div class="pl-card-footer">
                Verified loss records only
            </div>

        </div>

        <div class="pl-card">

            <div class="pl-card-top">
                <div class="pl-icon blue">
                    <i class="fa-solid fa-scale-balanced"></i>
                </div>
            </div>

            <div class="pl-label">Net Result</div>

            <h3 class="pl-value">
                {{ number_format($summary['net_result'], 2) }}
            </h3>

            <div class="pl-card-footer">
                Based on verified financial records
            </div>

        </div>

        <div class="pl-card">

            <div class="pl-card-top">
                <div class="pl-icon purple">
                    <i class="fa-solid fa-receipt"></i>
                </div>
            </div>

            <div class="pl-label">Total Records</div>

            <h3 class="pl-value">
                {{ number_format($summary['total_records']) }}
            </h3>

            <div class="pl-card-footer">
                Recorded profit and loss entries
            </div>

        </div>

    </div>

    {{-- Breakdown --}}
    <div class="pl-breakdown">

        <div class="pl-panel">

            <div class="pl-panel-header">
                <div>
                    <h3>Financial Breakdown</h3>
                    <p>Overview of recorded financial results.</p>
                </div>
            </div>

            <div class="pl-breakdown-content">

                <div class="pl-breakdown-row">

                    <div class="pl-breakdown-label">
                        <span class="pl-dot pl-profit"></span>
                        Profit Records
                    </div>

                    <div class="pl-breakdown-value">
                        {{ number_format($summary['profit_records']) }}
                    </div>

                </div>

                <div class="pl-breakdown-row">

                    <div class="pl-breakdown-label">
                        <span class="pl-dot pl-loss"></span>
                        Loss Records
                    </div>

                    <div class="pl-breakdown-value">
                        {{ number_format($summary['loss_records']) }}
                    </div>

                </div>

                <div class="pl-breakdown-row">

                    <div class="pl-breakdown-label">
                        <span class="pl-dot pl-neutral"></span>
                        Total Financial Records
                    </div>

                    <div class="pl-breakdown-value">
                        {{ number_format($summary['total_records']) }}
                    </div>

                </div>

            </div>

        </div>

        <div class="pl-panel">

            <div class="pl-panel-header">
                <div>
                    <h3>Performance Overview</h3>
                    <p>Summary of recorded profit and loss.</p>
                </div>
            </div>

            <div class="pl-breakdown-content">

                <div class="pl-breakdown-row">

                    <div class="pl-breakdown-label">
                        <i class="fa-solid fa-arrow-up" style="color:#087f5b;"></i>
                        Recorded Profit
                    </div>

                    <div class="pl-breakdown-value">
                        {{ number_format($summary['total_profit'], 2) }}
                    </div>

                </div>

                <div class="pl-breakdown-row">

                    <div class="pl-breakdown-label">
                        <i class="fa-solid fa-arrow-down" style="color:#dc3545;"></i>
                        Recorded Loss
                    </div>

                    <div class="pl-breakdown-value">
                        {{ number_format($summary['total_loss'], 2) }}
                    </div>

                </div>

                <div class="pl-breakdown-row">

                    <div class="pl-breakdown-label">
                        <i class="fa-solid fa-equals" style="color:#3975d7;"></i>
                        Net Financial Result
                    </div>

                    <div class="pl-breakdown-value">
                        {{ number_format($summary['net_result'], 2) }}
                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- History Table --}}
    <div class="pl-panel">

        <div class="pl-panel-header">

            <div>
                <h3>Profit & Loss Records</h3>

                <p>
                    Detailed history of your recorded financial results.
                </p>
            </div>

            <form method="GET"
                  action="{{ route('user.profit-loss-history.index') }}"
                  class="pl-filter">

                <select name="type" onchange="this.form.submit()">

                    <option value="all" {{ $type === 'all' ? 'selected' : '' }}>
                        All Records
                    </option>

                    <option value="profit" {{ $type === 'profit' ? 'selected' : '' }}>
                        Profit
                    </option>

                    <option value="loss" {{ $type === 'loss' ? 'selected' : '' }}>
                        Loss
                    </option>

                </select>

            </form>

        </div>

        <div class="pl-table-wrap">

            <table class="pl-table">

                <thead>
                    <tr>
                        <th>Reference ID</th>
                        <th>Project Name</th>
                        <th>Record Date</th>
                        <th>Record Type</th>
                        <th>Description</th>
                        <th>Amount</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($records as $record)

                        <tr>

                            <td>#{{ $record->id }}</td>

                            <td>{{ $record->project_name }}</td>

                            <td>{{ $record->record_date }}</td>

                            <td>
                                <span class="pl-type {{ $record->type }}">
                                    <i class="fa-solid fa-circle" style="font-size:6px;"></i>
                                    {{ ucfirst($record->type) }}
                                </span>
                            </td>

                            <td>{{ $record->description }}</td>

                            <td>
                                {{ number_format($record->amount, 2) }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6">

                                <div class="pl-empty">

                                    <div class="pl-empty-icon">
                                        <i class="fa-solid fa-file-invoice-dollar"></i>
                                    </div>

                                    <h4>No Financial Records Found</h4>

                                    <p>
                                        There are currently no verified
                                        profit or loss records associated
                                        with your account. Available records
                                        will appear here when the relevant
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

    {{-- Notice --}}
    <div class="pl-notice">

        <i class="fa-solid fa-shield-halved"></i>

        <div>

            <h4>Financial Information Notice</h4>

            <p>
                This page is currently a display-only interface.
                No profit, loss, dividend or investment return is being
                calculated or distributed. Any future financial records
                must be supported by verified accounting data, a lawful
                business model, applicable regulatory approvals and
                appropriate audit controls.
            </p>

        </div>

    </div>

</div>

@endsection