@extends('layouts.app')

@section('title', 'Share Transfer / Resale')

@section('content')

<div class="page-header">
    <div>
        <h2>Share Transfer / Resale</h2>
        <p>View share transfer and resale information.</p>
    </div>
</div>

<style>
    .transfer-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
        gap: 18px;
        margin-bottom: 25px;
    }

    .transfer-card {
        background: #fff;
        border: 1px solid #e8edf0;
        border-radius: 12px;
        padding: 22px;
    }

    .transfer-card .label {
        color: #84919f;
        font-size: 13px;
        margin-bottom: 10px;
    }

    .transfer-card h3 {
        font-size: 25px;
        color: #1e293b;
        margin: 0;
    }

    .transfer-panel {
        background: #fff;
        border: 1px solid #e8edf0;
        border-radius: 12px;
        padding: 22px;
        margin-bottom: 20px;
    }

    .transfer-panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 20px;
    }

    .transfer-filter select {
        padding: 10px 14px;
        border: 1px solid #e8edf0;
        border-radius: 7px;
        background: #fff;
        min-width: 150px;
    }

    .transfer-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 850px;
    }

    .transfer-table th,
    .transfer-table td {
        padding: 14px 12px;
        border-bottom: 1px solid #edf0f2;
        text-align: left;
        font-size: 13px;
    }

    .transfer-table th {
        color: #84919f;
        font-weight: 600;
        background: #f8fafb;
    }

    .transfer-empty {
        text-align: center;
        padding: 55px 15px;
    }

    .transfer-empty i {
        font-size: 45px;
        color: #cbd5e1;
        margin-bottom: 15px;
    }

    .transfer-empty h3 {
        color: #334155;
        margin-bottom: 8px;
    }

    .transfer-empty p {
        color: #84919f;
        font-size: 13px;
    }

    .transfer-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
        gap: 18px;
        margin-bottom: 20px;
    }

    .transfer-info-card {
        background: #fff;
        border: 1px solid #e8edf0;
        border-radius: 12px;
        padding: 22px;
    }

    .transfer-info-card i {
        font-size: 24px;
        color: #087f5b;
        margin-bottom: 14px;
    }

    .transfer-info-card h4 {
        margin: 0 0 8px;
        color: #1e293b;
    }

    .transfer-info-card p {
        color: #84919f;
        font-size: 13px;
        line-height: 1.7;
        margin: 0;
    }

    .transfer-notice {
        background: #fff8e6;
        border: 1px solid #f5dfa5;
        color: #856404;
        border-radius: 10px;
        padding: 16px;
        font-size: 13px;
        line-height: 1.7;
    }

    @media(max-width: 576px) {
        .transfer-panel {
            padding: 15px;
        }

        .transfer-filter,
        .transfer-filter select {
            width: 100%;
        }
    }
</style>

{{-- Summary --}}
<div class="transfer-summary">

    <div class="transfer-card">
        <div class="label">Total Records</div>
        <h3>{{ $summary['total_records'] }}</h3>
    </div>

    <div class="transfer-card">
        <div class="label">Pending Requests</div>
        <h3>{{ $summary['pending'] }}</h3>
    </div>

    <div class="transfer-card">
        <div class="label">Completed Transfers</div>
        <h3>{{ $summary['completed'] }}</h3>
    </div>

    <div class="transfer-card">
        <div class="label">Rejected Requests</div>
        <h3>{{ $summary['rejected'] }}</h3>
    </div>

</div>

{{-- Information Cards --}}
<div class="transfer-info">

    <div class="transfer-info-card">
        <i class="fa-solid fa-right-left"></i>
        <h4>Share Transfer</h4>
        <p>
            Information about transferring ownership between eligible
            parties, subject to applicable rules and verification.
        </p>
    </div>

    <div class="transfer-info-card">
        <i class="fa-solid fa-arrow-right-arrow-left"></i>
        <h4>Share Resale</h4>
        <p>
            Information about potential resale procedures, eligibility
            and applicable approval requirements.
        </p>
    </div>

    <div class="transfer-info-card">
        <i class="fa-solid fa-shield-halved"></i>
        <h4>Security & Verification</h4>
        <p>
            Any future transfer process will require appropriate identity
            checks, ownership verification and security controls.
        </p>
    </div>

</div>

{{-- Transfer History --}}
<div class="transfer-panel">

    <div class="transfer-panel-header">

        <div>
            <h3 style="margin:0 0 5px;">Transfer / Resale History</h3>

            <p style="margin:0;color:#84919f;font-size:13px;">
                Review your previous transfer and resale records.
            </p>
        </div>

        <form method="GET"
              action="{{ route('user.share-transfer.index') }}"
              class="transfer-filter">

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

        <table class="transfer-table">

            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>Request Type</th>
                    <th>Project</th>
                    <th>Share Reference</th>
                    <th>Request Date</th>
                    <th>Quantity</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

                @forelse($transfers as $transfer)

                    <tr>
                        <td>#{{ $transfer->id }}</td>
                        <td>{{ ucfirst($transfer->type) }}</td>
                        <td>{{ $transfer->project_name }}</td>
                        <td>{{ $transfer->share_reference }}</td>
                        <td>{{ $transfer->request_date }}</td>
                        <td>{{ $transfer->quantity }}</td>
                        <td>{{ ucfirst($transfer->status) }}</td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="7">

                            <div class="transfer-empty">

                                <i class="fa-solid fa-right-left"></i>

                                <h3>No Transfer Records Found</h3>

                                <p>
                                    You don't have any share transfer or
                                    resale records yet.
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
<div class="transfer-notice">

    <strong>
        <i class="fa-solid fa-circle-info"></i>
        Important Notice
    </strong>

    <p style="margin:8px 0 0;">
        Share transfer and resale services are currently inactive.
        No transfer, ownership change, sale or payment can be initiated
        from this page. Such services require a verified business model,
        applicable regulatory approvals and appropriate legal and
        security arrangements.
    </p>

</div>

@endsection