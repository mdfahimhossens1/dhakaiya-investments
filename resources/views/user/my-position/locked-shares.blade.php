@extends('layouts.app')

@section('title', 'Locked Shares')

@section('content')

<style>
    .ls-page { display:grid; gap:22px; }

    .ls-hero {
        background:linear-gradient(135deg,#102b26,#087f5b);
        padding:28px;
        color:#fff;
        border-radius:14px;
    }

    .ls-hero h2 { margin:0 0 8px; font-size:24px; }
    .ls-hero p { margin:0; color:#d5e9e1; font-size:14px; }

    .ls-summary {
        display:grid;
        grid-template-columns:repeat(3,1fr);
        gap:15px;
    }

    .ls-card {
        background:#fff;
        border:1px solid #e8edf0;
        border-radius:13px;
        padding:22px;
    }

    .ls-card h3 { margin:0 0 6px; font-size:24px; color:#1e293b; }
    .ls-card p { margin:0; color:#84919f; font-size:13px; }

    .ls-section h3 { margin:0 0 18px; font-size:18px; color:#1e293b; }

    .ls-empty {
        text-align:center;
        padding:40px 15px;
        background:#fafbfc;
        border:1px dashed #dce4e8;
        border-radius:10px;
    }

    .ls-empty i { font-size:35px; color:#b8c3cc; margin-bottom:13px; }
    .ls-empty h4 { margin:0 0 7px; color:#334155; }
    .ls-empty p { margin:0; color:#84919f; font-size:13px; }

    .ls-notice {
        background:#eff6ff;
        color:#1e40af;
        border:1px solid #dbeafe;
        padding:15px;
        border-radius:10px;
        font-size:13px;
    }

    @media(max-width:600px) {
        .ls-summary { grid-template-columns:1fr 1fr; }
        .ls-card { padding:16px; }
    }
</style>

<div class="ls-page">

    <div class="ls-hero">
        <h2><i class="fa-solid fa-lock"></i> Locked Shares</h2>
        <p>View your recorded locked share information and status.</p>
    </div>

    <div class="ls-summary">

        <div class="ls-card">
            <h3>{{ $summary['total_locked_shares'] }}</h3>
            <p>Total Locked Shares</p>
        </div>

        <div class="ls-card">
            <h3>{{ $summary['total_records'] }}</h3>
            <p>Total Records</p>
        </div>

        <div class="ls-card">
            <h3>{{ $summary['active_records'] }}</h3>
            <p>Active Records</p>
        </div>

    </div>

    <div class="ls-card ls-section">

        <h3>Locked Share Records</h3>

        @if($lockedShareRecords->isEmpty())

            <div class="ls-empty">
                <i class="fa-solid fa-lock"></i>
                <h4>No Locked Share Records</h4>
                <p>Any verified locked share records will appear here.</p>
            </div>

        @else

            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;min-width:650px;">
                    <thead>
                        <tr style="background:#f8faf9;text-align:left;">
                            <th style="padding:13px;">#</th>
                            <th style="padding:13px;">Project</th>
                            <th style="padding:13px;">Quantity</th>
                            <th style="padding:13px;">Lock Date</th>
                            <th style="padding:13px;">Unlock Date</th>
                            <th style="padding:13px;">Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($lockedShareRecords as $record)
                            <tr>
                                <td style="padding:13px;">{{ $loop->iteration }}</td>
                                <td style="padding:13px;">{{ $record->project_name }}</td>
                                <td style="padding:13px;">{{ $record->quantity }}</td>
                                <td style="padding:13px;">{{ $record->lock_date }}</td>
                                <td style="padding:13px;">{{ $record->unlock_date }}</td>
                                <td style="padding:13px;">{{ $record->status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        @endif

    </div>

    <div class="ls-notice">
        <i class="fa-solid fa-circle-info"></i>
        Locked share records are currently not configured. No share locking or unlocking operation is available on this page.
    </div>

</div>

@endsection