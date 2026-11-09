@extends('layouts.app')

@section('title', 'Monthly Leaderboard')

@section('content')

<div class="page-heading">
    <div>
        <h2>Monthly Leaderboard</h2>
        <p>Explore monthly platform participation and achievement rankings.</p>
    </div>
</div>

<style>
    .leaderboard-wrapper {
        display: flex;
        flex-direction: column;
        gap: 22px;
    }

    .leaderboard-navigation {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
    }

    .leaderboard-nav-card {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 17px;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        text-decoration: none;
        color: var(--text);
        transition: .2s ease;
    }

    .leaderboard-nav-card:hover,
    .leaderboard-nav-card.active {
        border-color: var(--primary);
        background: #f0faf5;
    }

    .leaderboard-nav-icon {
        width: 42px;
        height: 42px;
        border-radius: 11px;
        background: #e9f7f1;
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
        flex-shrink: 0;
    }

    .leaderboard-nav-card h4 {
        font-size: 14px;
        margin: 0 0 4px;
        color: var(--text);
    }

    .leaderboard-nav-card p {
        font-size: 11px;
        color: var(--muted);
        margin: 0;
    }

    .leaderboard-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }

    .leaderboard-card,
    .leaderboard-panel {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 22px;
    }

    .leaderboard-icon {
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

    .leaderboard-card h3 {
        font-size: 25px;
        margin: 0 0 5px;
        color: var(--text);
    }

    .leaderboard-card p {
        font-size: 13px;
        color: var(--muted);
        margin: 0;
    }

    .leaderboard-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .leaderboard-panel-header h3 {
        font-size: 18px;
        color: var(--text);
        margin: 0 0 5px;
    }

    .leaderboard-panel-header p {
        font-size: 13px;
        color: var(--muted);
        margin: 0;
    }

    .leaderboard-month-badge {
        padding: 9px 14px;
        border-radius: 8px;
        background: #e9f7f1;
        color: var(--primary-dark);
        font-size: 12px;
        font-weight: 600;
    }

    .leaderboard-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .leaderboard-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 650px;
    }

    .leaderboard-table th {
        background: #f8fafc;
        padding: 14px;
        text-align: left;
        font-size: 12px;
        color: var(--muted);
        font-weight: 600;
        border-bottom: 1px solid var(--border);
    }

    .leaderboard-table td {
        padding: 15px 14px;
        font-size: 13px;
        color: var(--text);
        border-bottom: 1px solid var(--border);
    }

    .leaderboard-empty {
        text-align: center;
        padding: 45px 20px;
    }

    .leaderboard-empty-icon {
        width: 65px;
        height: 65px;
        margin: 0 auto 15px;
        border-radius: 50%;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        font-size: 26px;
    }

    .leaderboard-empty h4 {
        font-size: 17px;
        color: var(--text);
        margin-bottom: 8px;
    }

    .leaderboard-empty p {
        max-width: 460px;
        margin: 0 auto;
        color: var(--muted);
        font-size: 13px;
        line-height: 1.7;
    }

    .leaderboard-notice {
        padding: 16px 18px;
        border-radius: 12px;
        background: #eff6ff;
        border: 1px solid #dbeafe;
        color: #1e40af;
        font-size: 13px;
        line-height: 1.7;
    }

    @media(max-width: 800px) {
        .leaderboard-stats {
            grid-template-columns: repeat(2, 1fr);
        }

        .leaderboard-navigation {
            grid-template-columns: 1fr;
        }
    }

    @media(max-width: 550px) {
        .leaderboard-stats {
            grid-template-columns: 1fr;
        }

        .leaderboard-card,
        .leaderboard-panel {
            padding: 17px;
        }
    }
</style>

<div class="leaderboard-wrapper">

    {{-- Leaderboard Navigation --}}
    <div class="leaderboard-navigation">

        <a href="{{ route('user.leaderboard.index') }}"
           class="leaderboard-nav-card">

            <div class="leaderboard-nav-icon">
                <i class="fa-solid fa-ranking-star"></i>
            </div>

            <div>
                <h4>Resell Leaderboard</h4>
                <p>Resell ranking overview</p>
            </div>

        </a>

        <a href="{{ route('user.leaderboard.monthly') }}"
           class="leaderboard-nav-card active">

            <div class="leaderboard-nav-icon">
                <i class="fa-solid fa-calendar-days"></i>
            </div>

            <div>
                <h4>Monthly Leaderboard</h4>
                <p>Monthly participation ranking</p>
            </div>

        </a>

        <a href="{{ route('user.leaderboard.top-performers') }}"
           class="leaderboard-nav-card">

            <div class="leaderboard-nav-icon">
                <i class="fa-solid fa-trophy"></i>
            </div>

            <div>
                <h4>Top Performers</h4>
                <p>Platform achievement highlights</p>
            </div>

        </a>

    </div>

    {{-- Summary --}}
    <div class="leaderboard-stats">

        <div class="leaderboard-card">
            <div class="leaderboard-icon">
                <i class="fa-solid fa-users"></i>
            </div>

            <h3>{{ $summary['total_participants'] ?? 0 }}</h3>
            <p>Monthly Participants</p>
        </div>

        <div class="leaderboard-card">
            <div class="leaderboard-icon">
                <i class="fa-solid fa-ranking-star"></i>
            </div>

            <h3>{{ $summary['your_rank'] ?? 'N/A' }}</h3>
            <p>Your Monthly Rank</p>
        </div>

        <div class="leaderboard-card">
            <div class="leaderboard-icon">
                <i class="fa-solid fa-calendar-check"></i>
            </div>

            <h3>{{ $summary['monthly_referrals'] ?? 0 }}</h3>
            <p>Monthly Participation</p>
        </div>

    </div>

    {{-- Monthly Leaderboard Table --}}
    <div class="leaderboard-panel">

        <div class="leaderboard-panel-header">

            <div>
                <h3>Monthly Top Participants</h3>
                <p>Platform participation and achievement rankings.</p>
            </div>

            <div class="leaderboard-month-badge">
                <i class="fa-regular fa-calendar"></i>
                {{ $month }}
            </div>

        </div>

        <div class="leaderboard-table-wrapper">

            <table class="leaderboard-table">

                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Participant</th>
                        <th>Achievement Points</th>
                        <th>Completed Achievements</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($leaderboard as $participant)

                        <tr>
                            <td>{{ $participant->rank }}</td>
                            <td>{{ $participant->name }}</td>
                            <td>{{ $participant->points }}</td>
                            <td>{{ $participant->completed_achievements }}</td>
                            <td>{{ $participant->status }}</td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="5">

                                <div class="leaderboard-empty">

                                    <div class="leaderboard-empty-icon">
                                        <i class="fa-solid fa-calendar-xmark"></i>
                                    </div>

                                    <h4>Monthly Ranking Not Available</h4>

                                    <p>
                                        No monthly ranking data is available yet.
                                        The platform's monthly participation
                                        criteria and achievement point system
                                        have not been configured.
                                    </p>

                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    {{-- Information --}}
    <div class="leaderboard-notice">

        <i class="fa-solid fa-circle-info"></i>

        <strong> Important Notice:</strong>

        Monthly rankings will be displayed after the platform defines
        its participation criteria, monthly calculation period,
        achievement points and verification process.
        No financial ranking or investment-based competition is activated.

    </div>

</div>

@endsection