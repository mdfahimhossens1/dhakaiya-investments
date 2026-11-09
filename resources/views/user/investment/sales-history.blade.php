@extends('layouts.app')

@section('title', 'Sales History')

@section('content')

<div class="page-header">
    <div>
        <h2>Sales History</h2>
        <p>View your previous share sale records.</p>
    </div>
</div>

<style>
    .sales-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
        gap: 18px;
        margin-bottom: 25px;
    }

    .sales-card {
        background: #fff;
        border: 1px solid #e8edf0;
        border-radius: 12px;
        padding: 22px;
    }

    .sales-card .label {
        color: #84919f;
        font-size: 13px;
        margin-bottom: 10px;
    }

    .sales-card h3 {
        font-size: 25px;
        margin: 0;
        color: #1e293b;
    }

    .sales-panel {
        background: #fff;
        border: 1px solid #e8edf0;
        border-radius: 12px;
        padding: 22px;
        margin-bottom: 20px;
    }

    .sales-panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 20px;
    }

    .sales-filter {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .sales-filter select {
        padding: 10px 14px;
        border: 1px solid #e8edf0;
        border-radius: 7px;
        background: #fff;
        min-width: 150px;
    }

    .sales-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 850px;
    }

    .sales-table th,
    .sales-table td {
        padding: 14px 12px;
        border-bottom: 1px solid #edf0f2;
        text-align: left;
        font-size: 13px;
    }

    .sales-table th {
        color: #84919f;
        font-weight: 600;
        background: #f8fafb;
    }

    .sales-empty {
        text-align: center;
        padding: 55px 15px;
    }

    .sales-empty i {
        font-size: 45px;
        color: #cbd5e1;
        margin-bottom: 15px;
    }

    .sales-empty h3 {
        color: #334155;
        margin-bottom: 8px;
    }

    .sales-empty p {
        color: #84919f;
        font-size: 13px;
    }

    .sales-notice {
        background: #fff8e6;
        border: 1px solid #f5dfa5;
        color: #856404;
        border-radius: 10px;
        padding: 16px;
        font-size: 13px;
        line-height: 1.7;
    }

    .status-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    @media(max-width: 576px) {
        .sales-panel {
            padding: 15px;
        }

        .sales-filter {
            width: 100%;
        }

        .sales-filter select {
            width: 100%;
        }
    }
</style>

{{-- Summary --}}
<div class="sales-summary">

    <div class="sales-card">
        <div class="label">Total Sales Records</div>
        <h3>{{ $summary['total_records'] }}</h3>
    </div>

    <div class="sales-card">
        <div class="label">Pending Sales</div>
        <h3>{{ $summary['pending'] }}</h3>
    </div>

    <div class="sales-card">
        <div class="label">Completed Sales</div>
        <h3>{{ $summary['completed'] }}</h3>
    </div>

    <div class="sales-card">
        <div class="label">Rejected Sales</div>
        <h3>{{ $summary['rejected'] }}</h3>
    </div>

</div>

{{-- Sales Table --}}
<div class="sales-panel">

    <div class="sales-panel-header">

        <div>
            <h3 style="margin:0 0 5px;">My Sales Records</h3>
            <p style="margin:0;color:#84919f;font-size:13px;">
                Review your share sale history.
            </p>
        </div>

        <form method="GET"
              action="{{ route('user.sales-history.index') }}"
              class="sales-filter">

            <select name="status" onchange="this.form.submit()">
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

        </form>

    </div>

    <div style="overflow-x:auto;">

        <table class="sales-table">

            <thead>
                <tr>
                    <th>Sale ID</th>
                    <th>Project Name</th>
                    <th>Share Reference</th>
                    <th>Sale Date</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Details</th>
                </tr>
            </thead>

            <tbody>

                @forelse($sales as $sale)

                    <tr>
                        <td>#{{ $sale->id }}</td>
                        <td>{{ $sale->project_name }}</td>
                        <td>{{ $sale->share_reference }}</td>
                        <td>{{ $sale->sale_date }}</td>
                        <td>{{ $sale->quantity }}</td>
                        <td>{{ ucfirst($sale->status) }}</td>
                        <td>—</td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="7">

                            <div class="sales-empty">

                                <i class="fa-solid fa-chart-line"></i>

                                <h3>No Sales Records Found</h3>

                                <p>
                                    You don't have any share sale records yet.
                                    Your sales history will appear here when
                                    the relevant feature becomes available.
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
<div class="sales-notice">

    <strong>
        <i class="fa-solid fa-shield-halved"></i>
        Important Notice
    </strong>

    <p style="margin:8px 0 0;">
        Share sales and resale functionality are currently not activated.
        Any future transaction feature will require verified business
        rules, applicable regulatory approval and appropriate security
        controls.
    </p>

</div>

@endsection