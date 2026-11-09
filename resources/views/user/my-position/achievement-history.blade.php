@extends('layouts.app')

@section('title', 'Achievement History')

@section('content')

<style>
    .ah-page { display:grid; gap:22px; }

    .ah-hero {
        background:linear-gradient(135deg,#102b26,#087f5b);
        padding:28px;
        color:#fff;
        border-radius:14px;
    }

    .ah-hero h2 { margin:0 0 8px; font-size:24px; }
    .ah-hero p { margin:0; color:#d5e9e1; font-size:14px; }

    .ah-summary {
        display:grid;
        grid-template-columns:repeat(3,1fr);
        gap:15px;
    }

    .ah-card {
        background:#fff;
        border:1px solid #e8edf0;
        border-radius:13px;
        padding:22px;
    }

    .ah-card h3 { margin:0 0 6px; font-size:24px; color:#1e293b; }
    .ah-card p { margin:0; color:#84919f; font-size:13px; }

    .ah-section h3 { margin:0 0 18px; font-size:18px; color:#1e293b; }

    .ah-empty {
        text-align:center;
        padding:40px 15px;
        background:#fafbfc;
        border:1px dashed #dce4e8;
        border-radius:10px;
    }

    .ah-empty i { font-size:35px; color:#b8c3cc; margin-bottom:13px; }
    .ah-empty h4 { margin:0 0 7px; color:#334155; }
    .ah-empty p { margin:0; color:#84919f; font-size:13px; }

    .ah-notice {
        background:#eff6ff;
        color:#1e40af;
        border:1px solid #dbeafe;
        padding:15px;
        border-radius:10px;
        font-size:13px;
    }

    @media(max-width:600px) {
        .ah-summary { grid-template-columns:1fr 1fr; }
        .ah-card { padding:16px; }
    }
</style>

<div class="ah-page">

    <div class="ah-hero">
        <h2><i class="fa-solid fa-trophy"></i> Achievement History</h2>
        <p>Track your recorded achievements and milestones.</p>
    </div>

    <div class="ah-summary">

        <div class="ah-card">
            <h3>{{ $summary['total_achievements'] }}</h3>
            <p>Total Achievements</p>
        </div>

        <div class="ah-card">
            <h3>{{ $summary['completed'] }}</h3>
            <p>Completed</p>
        </div>

        <div class="ah-card">
            <h3>{{ $summary['pending'] }}</h3>
            <p>Pending</p>
        </div>

    </div>

    <div class="ah-card ah-section">

        <h3>Achievement Records</h3>

        @if($achievementHistory->isEmpty())

            <div class="ah-empty">
                <i class="fa-solid fa-award"></i>
                <h4>No Achievement History Found</h4>
                <p>Your verified achievement records will appear here.</p>
            </div>

        @else

            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;min-width:600px;">
                    <thead>
                        <tr style="background:#f8faf9;text-align:left;">
                            <th style="padding:13px;">#</th>
                            <th style="padding:13px;">Achievement</th>
                            <th style="padding:13px;">Category</th>
                            <th style="padding:13px;">Date</th>
                            <th style="padding:13px;">Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($achievementHistory as $achievement)
                            <tr>
                                <td style="padding:13px;">{{ $loop->iteration }}</td>
                                <td style="padding:13px;">{{ $achievement->title }}</td>
                                <td style="padding:13px;">{{ $achievement->category }}</td>
                                <td style="padding:13px;">{{ $achievement->date }}</td>
                                <td style="padding:13px;">{{ $achievement->status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        @endif

    </div>

    <div class="ah-notice">
        <i class="fa-solid fa-circle-info"></i>
        Achievement history is currently not configured. Verified achievement records will appear here when available.
    </div>

</div>

@endsection