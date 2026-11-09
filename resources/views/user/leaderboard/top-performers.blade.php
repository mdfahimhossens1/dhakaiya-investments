@extends('layouts.app')

@section('title', 'Top Performers')

@section('content')

<div class="page-heading">
    <div>
        <h2>Top Performers</h2>
        <p>Explore platform achievements and outstanding participation.</p>
    </div>
</div>

<style>
    .performers-wrapper {
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

    .performers-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }

    .performer-card,
    .performer-panel {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 22px;
    }

    .performer-icon {
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

    .performer-card h3 {
        font-size: 25px;
        margin: 0 0 5px;
        color: var(--text);
    }

    .performer-card p {
        font-size: 13px;
        color: var(--muted);
        margin: 0;
    }

    .performer-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .performer-panel-header h3 {
        font-size: 18px;
        color: var(--text);
        margin: 0 0 5px;
    }

    .performer-panel-header p {
        font-size: 13px;
        color: var(--muted);
        margin: 0;
    }

    .performer-badge {
        padding: 9px 14px;
        border-radius: 8px;
        background: #e9f7f1;
        color: var(--primary-dark);
        font-size: 12px;
        font-weight: 600;
    }

    .performer-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .performer-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 650px;
    }

    .performer-table th {
        background: #f8fafc;
        padding: 14px;
        text-align: left;
        font-size: 12px;
        color: var(--muted);
        font-weight: 600;
        border-bottom: 1px solid var(--border);
    }

    .performer-table td {
        padding: 15px 14px;
        font-size: 13px;
        color: var(--text);
        border-bottom: 1px solid var(--border);
    }

    .performer-category-list {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 14px;
    }

    .performer-category {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 17px;
        border: 1px solid var(--border);
        border-radius: 12px;
        background: #fff;
    }

    .performer-category-icon {
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

    .performer-category h4 {
        font-size: 13px;
        color: var(--text);
        margin: 0 0 4px;
    }

    .performer-category p {
        font-size: 11px;
        color: var(--muted);
        margin: 0;
    }

    .performer-empty {
        text-align: center;
        padding: 45px 20px;
    }

    .performer-empty-icon {
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

    .performer-empty h4 {
        font-size: 17px;
        color: var(--text);
        margin-bottom: 8px;
    }

    .performer-empty p {
        max-width: 460px;
        margin: 0 auto;
        color: var(--muted);
        font-size: 13px;
        line-height: 1.7;
    }

    .performer-notice {
        padding: 16px 18px;
        border-radius: 12px;
        background: #eff6ff;
        border: 1px solid #dbeafe;
        color: #1e40af;
        font-size: 13px;
        line-height: 1.7;
    }

    @media(max-width: 800px) {
        .performers-stats {
            grid-template-columns: repeat(2, 1fr);
        }

        .leaderboard-navigation {
            grid-template-columns: 1fr;
        }
    }

    @media(max-width: 550px) {
        .performers-stats,
        .performer-category-list {
            grid-template-columns: 1fr;
        }

        .performer-card,
        .performer-panel {
            padding: 17px;
        }
    }
</style>

<div class="performers-wrapper">

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
           class="leaderboard-nav-card">

            <div class="leaderboard-nav-icon">
                <i class="fa-solid fa-calendar-days"></i>
            </div>

            <div>
                <h4>Monthly Leaderboard</h4>
                <p>Monthly participation ranking</p>
            </div>

        </a>

        <a href="{{ route('user.leaderboard.top-performers') }}"
           class="leaderboard-nav-card active">

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
    <div class="performers-stats">

        <div class="performer-card">
            <div class="performer-icon">
                <i class="fa-solid fa-users"></i>
            </div>

            <h3>{{ $summary['total_performers'] ?? 0 }}</h3>
            <p>Total Performers</p>
        </div>

        <div class="performer-card">
            <div class="performer-icon">
                <i class="fa-solid fa-circle-check"></i>
            </div>

            <h3>{{ $summary['verified_performers'] ?? 0 }}</h3>
            <p>Verified Performers</p>
        </div>

        <div class="performer-card">
            <div class="performer-icon">
                <i class="fa-solid fa-ranking-star"></i>
            </div>

            <h3>{{ $summary['your_position'] ?? 'N/A' }}</h3>
            <p>Your Position</p>
        </div>

    </div>

    {{-- Top Performers Table --}}
    <div class="performer-panel">

        <div class="performer-panel-header">

            <div>
                <h3>Top Platform Performers</h3>
                <p>Outstanding platform participation and achievements.</p>
            </div>

            <div class="performer-badge">
                <i class="fa-solid fa-award"></i>
                Top Performers
            </div>

        </div>

        <div class="performer-table-wrapper">

            <table class="performer-table">

                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Participant</th>
                        <th>Category</th>
                        <th>Achievement Points</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($performers as $performer)

                        <tr>
                            <td>{{ $performer->rank }}</td>
                            <td>{{ $performer->name }}</td>
                            <td>{{ $performer->category }}</td>
                            <td>{{ $performer->points }}</td>
                            <td>{{ $performer->status }}</td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="5">

                                <div class="performer-empty">

                                    <div class="performer-empty-icon">
                                        <i class="fa-solid fa-trophy"></i>
                                    </div>

                                    <h4>No Top Performers Yet</h4>

                                    <p>
                                        Top performer information is not
                                        available yet. The platform's
                                        achievement criteria, scoring rules
                                        and verification process need to be
                                        configured first.
                                    </p>

                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    {{-- Performance Categories --}}
    <div class="performer-panel">

        <div class="performer-panel-header">

            <div>
                <h3>Performance Categories</h3>
                <p>Platform activities that may be considered for recognition.</p>
            </div>

        </div>

        <div class="performer-category-list">

            @forelse($categories as $category)

                <div class="performer-category">

                    <div class="performer-category-icon">
                        <i class="fa-solid fa-medal"></i>
                    </div>

                    <div>
                        <h4>{{ $category }}</h4>
                        <p>Criteria not configured yet</p>
                    </div>

                </div>

            @empty

                <div class="performer-empty">
                    <p>No performance categories have been configured yet.</p>
                </div>

            @endforelse

        </div>

    </div>

    {{-- Information --}}
    <div class="performer-notice">

        <i class="fa-solid fa-circle-info"></i>

        <strong> Important Notice:</strong>

        Top performer recognition will be available after the platform
        defines achievement categories, scoring rules and verification
        procedures. Rankings are based on platform participation only.
        No financial rewards, investment returns or commissions are
        activated through this page.

    </div>

</div>

@endsection