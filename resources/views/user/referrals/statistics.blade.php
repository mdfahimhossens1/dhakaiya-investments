@extends('layouts.app')

@section('title', 'Referral Statistics')

@section('content')

<div class="page-heading">
    <div>
        <h2>Referral Statistics</h2>
        <p>Track your referral registration statistics.</p>
    </div>
</div>

<style>
    .statistics-wrapper {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .statistics-cards {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }

    .statistics-card,
    .statistics-panel {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 22px;
    }

    .statistics-icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        background: #e9f7f1;
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
        margin-bottom: 15px;
    }

    .statistics-card h3 {
        font-size: 25px;
        color: var(--text);
        margin: 0 0 5px;
    }

    .statistics-card p {
        font-size: 13px;
        color: var(--muted);
        margin: 0;
    }

    .statistics-panel h3 {
        font-size: 18px;
        color: var(--text);
        margin: 0 0 5px;
    }

    .statistics-subtitle {
        font-size: 13px;
        color: var(--muted);
        margin-bottom: 22px;
    }

    .statistics-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-bottom: 17px;
    }

    .statistics-row:last-child {
        margin-bottom: 0;
    }

    .statistics-month {
        width: 100px;
        font-size: 13px;
        color: var(--text);
        flex-shrink: 0;
    }

    .statistics-progress {
        flex: 1;
        height: 9px;
        background: #f1f5f9;
        border-radius: 20px;
        overflow: hidden;
    }

    .statistics-progress span {
        display: block;
        height: 100%;
        background: var(--primary);
        border-radius: 20px;
    }

    .statistics-count {
        width: 35px;
        text-align: right;
        font-size: 13px;
        font-weight: 600;
        color: var(--text);
    }

    .statistics-notice {
        padding: 16px 18px;
        border-radius: 12px;
        background: #eff6ff;
        border: 1px solid #dbeafe;
        color: #1e40af;
        font-size: 13px;
        line-height: 1.7;
    }

    @media(max-width: 800px) {
        .statistics-cards {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media(max-width: 550px) {
        .statistics-cards {
            grid-template-columns: 1fr;
        }

        .statistics-card,
        .statistics-panel {
            padding: 17px;
        }

        .statistics-month {
            width: 75px;
            font-size: 11px;
        }
    }
</style>

<div class="statistics-wrapper">

    {{-- Summary --}}
    <div class="statistics-cards">

        <div class="statistics-card">
            <div class="statistics-icon">
                <i class="fa-solid fa-users"></i>
            </div>

            <h3>{{ $summary['total_referrals'] }}</h3>
            <p>Total Referrals</p>
        </div>

        <div class="statistics-card">
            <div class="statistics-icon">
                <i class="fa-solid fa-calendar-days"></i>
            </div>

            <h3>{{ $summary['this_month'] }}</h3>
            <p>This Month</p>
        </div>

        <div class="statistics-card">
            <div class="statistics-icon">
                <i class="fa-solid fa-chart-line"></i>
            </div>

            <h3>{{ $summary['this_year'] }}</h3>
            <p>This Year</p>
        </div>

    </div>

    {{-- Monthly Statistics --}}
    <div class="statistics-panel">

        <h3>Monthly Referral Overview</h3>

        <p class="statistics-subtitle">
            New member registrations during the last six months.
        </p>

        @php
            $maxReferrals = max(
                $monthlyStatistics->max('total'),
                1
            );
        @endphp

        @foreach($monthlyStatistics as $stat)

            <div class="statistics-row">

                <div class="statistics-month">
                    {{ $stat['month'] }}
                </div>

                <div class="statistics-progress">
                    <span style="width: {{ ($stat['total'] / $maxReferrals) * 100 }}%;"></span>
                </div>

                <div class="statistics-count">
                    {{ $stat['total'] }}
                </div>

            </div>

        @endforeach

    </div>

    <div class="statistics-notice">

        <i class="fa-solid fa-circle-info"></i>

        <strong>Information:</strong>

        These statistics represent referral registrations only.
        They do not represent investment activity, commission,
        earnings or financial returns.

    </div>

</div>

@endsection