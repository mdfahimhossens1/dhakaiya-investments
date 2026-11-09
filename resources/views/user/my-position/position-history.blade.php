@extends('layouts.app')

@section('title', 'Position History')

@section('content')

<style>
    .ph-page { display:grid; gap:22px; }

    .ph-hero {
        background:linear-gradient(135deg,#102b26,#087f5b);
        padding:28px;
        color:#fff;
        border-radius:14px;
    }

    .ph-hero h2 { margin:0 0 8px; font-size:24px; }
    .ph-hero p { margin:0; color:#d5e9e1; font-size:14px; }

    .ph-card {
        background:#fff;
        border:1px solid #e8edf0;
        border-radius:13px;
        padding:22px;
    }

    .ph-summary {
        display:grid;
        grid-template-columns:repeat(3,1fr);
        gap:15px;
    }

    .ph-summary h3 { font-size:24px; margin:0 0 5px; color:#1e293b; }
    .ph-summary p { font-size:13px; color:#84919f; margin:0; }

    .ph-card h3 { font-size:18px; margin:0 0 18px; color:#1e293b; }

    .ph-empty {
        text-align:center;
        padding:40px 15px;
        background:#fafbfc;
        border:1px dashed #dce4e8;
        border-radius:10px;
    }

    .ph-empty i { font-size:35px; color:#b8c3cc; margin-bottom:13px; }
    .ph-empty h4 { margin:0 0 7px; color:#334155; }
    .ph-empty p { margin:0; color:#84919f; font-size:13px; }

    .ph-notice {
        background:#eff6ff;
        color:#1e40af;
        border:1px solid #dbeafe;
        padding:15px;
        border-radius:10px;
        font-size:13px;
    }

    @media(max-width:600px) {
        .ph-summary { grid-template-columns:1fr 1fr; }
        .ph-card { padding:16px; }
    }
</style>

<div class="ph-page">

    <div class="ph-hero">
        <h2><i class="fa-solid fa-clock-rotate-left"></i> Position History</h2>
        <p>Review your previous position records and changes.</p>
    </div>

    <div class="ph-summary">

        <div class="ph-card">
            <h3>{{ $summary['total_positions'] }}</h3>
            <p>Total Positions</p>
        </div>

        <div class="ph-card">
            <h3>{{ $summary['current_level'] }}</h3>
            <p>Current Level</p>
        </div>

        <div class="ph-card">
            <h3>{{ $summary['previous_positions'] }}</h3>
            <p>Previous Positions</p>
        </div>

    </div>

    <div class="ph-card">

        <h3>Position History Records</h3>

        @if($positionHistory->isEmpty())

            <div class="ph-empty">
                <i class="fa-solid fa-clock-rotate-left"></i>
                <h4>No Position History Found</h4>
                <p>Your previous position records will appear here.</p>
            </div>

        @else

            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;min-width:600px;">
                    <thead>
                        <tr style="background:#f8faf9;text-align:left;">
                            <th style="padding:13px;">#</th>
                            <th style="padding:13px;">Position</th>
                            <th style="padding:13px;">Level</th>
                            <th style="padding:13px;">Assigned Date</th>
                            <th style="padding:13px;">Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($positionHistory as $history)
                            <tr>
                                <td style="padding:13px;">{{ $loop->iteration }}</td>
                                <td style="padding:13px;">{{ $history->position }}</td>
                                <td style="padding:13px;">{{ $history->level }}</td>
                                <td style="padding:13px;">{{ $history->assigned_date }}</td>
                                <td style="padding:13px;">{{ $history->status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        @endif

    </div>

    <div class="ph-notice">
        <i class="fa-solid fa-circle-info"></i>
        Position history is currently not configured. Verified records will appear here when available.
    </div>

</div>

@endsection