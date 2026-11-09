@extends('layouts.app')

@section('title', 'Current Position')

@section('content')

<style>
    .mp-page { display:grid; gap:22px; }

    .mp-hero {
        background:linear-gradient(135deg,#102b26,#087f5b);
        color:#fff;
        padding:30px;
        border-radius:15px;
        position:relative;
        overflow:hidden;
    }

    .mp-hero h2 {
        font-size:25px;
        font-weight:700;
        margin:0 0 9px;
    }

    .mp-hero p {
        color:#d5e9e1;
        font-size:14px;
        margin:0;
    }

    .mp-hero i {
        position:absolute;
        right:35px;
        top:20px;
        font-size:75px;
        opacity:.15;
    }

    .mp-grid {
        display:grid;
        grid-template-columns:repeat(3,1fr);
        gap:16px;
    }

    .mp-card,.mp-section {
        background:#fff;
        border:1px solid #e8edf0;
        border-radius:13px;
        padding:22px;
    }

    .mp-card-icon {
        width:43px;
        height:43px;
        border-radius:10px;
        background:#e8f5ef;
        color:#087f5b;
        display:flex;
        align-items:center;
        justify-content:center;
        margin-bottom:14px;
        font-size:18px;
    }

    .mp-card h3 {
        margin:0 0 5px;
        font-size:23px;
        color:#1e293b;
    }

    .mp-card p {
        margin:0;
        font-size:13px;
        color:#84919f;
    }

    .mp-section h3 {
        font-size:18px;
        color:#1e293b;
        margin:0 0 20px;
        font-weight:700;
    }

    .mp-details {
        display:grid;
        grid-template-columns:repeat(2,1fr);
        gap:15px;
    }

    .mp-detail {
        background:#f8faf9;
        border:1px solid #e8edf0;
        padding:18px;
        border-radius:10px;
    }

    .mp-detail span {
        display:block;
        color:#84919f;
        font-size:13px;
        margin-bottom:8px;
    }

    .mp-detail strong {
        color:#1e293b;
        font-size:16px;
    }

    .mp-status {
        display:inline-block;
        background:#fff4db;
        color:#946200;
        padding:6px 12px;
        border-radius:30px;
        font-size:12px;
        font-weight:700;
    }

    .mp-notice {
        background:#eff6ff;
        color:#1e40af;
        border:1px solid #dbeafe;
        padding:16px;
        border-radius:10px;
        font-size:13px;
        line-height:1.7;
    }

    @media(max-width:768px) {
        .mp-grid { grid-template-columns:repeat(2,1fr); }
        .mp-details { grid-template-columns:1fr; }
    }

    @media(max-width:480px) {
        .mp-hero { padding:22px; }
        .mp-hero h2 { font-size:21px; }
        .mp-grid { grid-template-columns:1fr 1fr; gap:10px; }
        .mp-card { padding:15px; }
        .mp-card h3 { font-size:19px; }
        .mp-section { padding:17px; }
    }
</style>

<div class="mp-page">

    <div class="mp-hero">
        <i class="fa-solid fa-chess-king"></i>

        <h2>Current Position</h2>

        <p>
            View your current global position and account ranking information.
        </p>
    </div>

    <div class="mp-grid">

        <div class="mp-card">
            <div class="mp-card-icon">
                <i class="fa-solid fa-ranking-star"></i>
            </div>

            <h3>{{ $currentPosition['rank'] }}</h3>
            <p>Current Rank</p>
        </div>

        <div class="mp-card">
            <div class="mp-card-icon">
                <i class="fa-solid fa-layer-group"></i>
            </div>

            <h3>{{ $summary['current_level'] }}</h3>
            <p>Current Level</p>
        </div>

        <div class="mp-card">
            <div class="mp-card-icon">
                <i class="fa-solid fa-trophy"></i>
            </div>

            <h3>{{ $summary['total_achievements'] }}</h3>
            <p>Total Achievements</p>
        </div>

    </div>

    <div class="mp-section">

        <h3>
            <i class="fa-solid fa-chess-king" style="color:#087f5b;"></i>
            Position Details
        </h3>

        <div class="mp-details">

            <div class="mp-detail">
                <span>Current Position</span>
                <strong>{{ $currentPosition['rank'] }}</strong>
            </div>

            <div class="mp-detail">
                <span>Position Level</span>
                <strong>{{ $currentPosition['level'] }}</strong>
            </div>

            <div class="mp-detail">
                <span>Position Status</span>
                <span class="mp-status">
                    {{ $currentPosition['status'] }}
                </span>
            </div>

            <div class="mp-detail">
                <span>Assigned Date</span>
                <strong>
                    {{ $currentPosition['assigned_date'] ?? 'Not Assigned' }}
                </strong>
            </div>

            <div class="mp-detail">
                <span>Achievements</span>
                <strong>{{ $currentPosition['achievements'] }}</strong>
            </div>

            <div class="mp-detail">
                <span>Account Holder</span>
                <strong>{{ $user->name }}</strong>
            </div>

        </div>

    </div>

    <div class="mp-notice">
        <i class="fa-solid fa-circle-info"></i>

        Position information is currently not configured.
        Verified position records will be displayed here when available.
    </div>

</div>

@endsection