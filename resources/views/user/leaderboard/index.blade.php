@extends('layouts.app')

@section('title', 'Leaderboard')

@section('content')

<div class="page-heading">
    <div>
        <h2>Leaderboard</h2>
        <p>Explore platform participation and achievement rankings.</p>
    </div>
</div>

<style>
    .leaderboard-wrapper {
        display: flex;
        flex-direction: column;
        gap: 22px;
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

    .leaderboard-filter {
        padding: 10px 13px;
        border: 1px solid var(--border);
        border-radius: 8px;
        background: #fff;
        color: var(--text);
        font-size: 13px;
        outline: none;
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
            grid-template-columns: 1fr 1fr;
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

    {{-- Summary --}}
    <div class="leaderboard-stats">

        <div class="leaderboard-card">
            <div class="leaderboard-icon">
                <i class="fa-solid fa-users"></i>
            </div>

            <h3>{{ $summary['total_participants'] }}</h3>
            <p>Total Participants</p>
        </div>

        <div class="leaderboard-card">
            <div class="leaderboard-icon">
                <i class="fa-solid fa-ranking-star"></i>
            </div>

            <h3>{{ $summary['your_rank'] ?? 'N/A' }}</h3>
            <p>Your Current Rank</p>
        </div>

        <div class="leaderboard-card">
            <div class="leaderboard-icon">
                <i class="fa-solid fa-trophy"></i>
            </div>

            <h3>{{ $summary['top_rank'] ?? 'N/A' }}</h3>
            <p>Top Rank</p>
        </div>

    </div>

    {{-- Leaderboard Table --}}
    <div class="leaderboard-panel">

        <div class="leaderboard-panel-header">

            <div>
                <h3>Top Participants</h3>
                <p>Platform participation and achievement rankings.</p>
            </div>

            <select class="leaderboard-filter" disabled>
                @foreach($periods as $key => $label)
                    <option value="{{ $key }}"
                        {{ $period === $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>

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
                                        <i class="fa-solid fa-trophy"></i>
                                    </div>

                                    <h4>Leaderboard Not Available</h4>

                                    <p>
                                        No ranking data is available yet.
                                        The platform's ranking criteria and
                                        achievement point system have not
                                        been configured.
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

        Leaderboard rankings will be displayed after the platform
        defines its participation criteria, achievement points,
        ranking period and verification process. No financial
        ranking or investment-based competition is activated.
    </div>

</div>

@endsection