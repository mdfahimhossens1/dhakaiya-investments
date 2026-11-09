@extends('layouts.app')

@section('title', 'Achievement Rewards')

@section('content')

<div class="page-heading">
    <div>
        <h2>Achievement Rewards</h2>
        <p>Explore your milestones and platform achievements.</p>
    </div>
</div>

<style>
    .achievement-wrapper {
        display: flex;
        flex-direction: column;
        gap: 22px;
    }

    .achievement-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }

    .achievement-card,
    .achievement-category,
    .achievement-empty {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 22px;
    }

    .achievement-card .stat-icon {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #e9f7f1;
        color: var(--primary);
        font-size: 18px;
        margin-bottom: 15px;
    }

    .achievement-card h3 {
        font-size: 25px;
        margin: 0 0 5px;
        color: var(--text);
    }

    .achievement-card p {
        color: var(--muted);
        font-size: 13px;
        margin: 0;
    }

    .achievement-section-title {
        margin-bottom: 16px;
    }

    .achievement-section-title h3 {
        font-size: 18px;
        margin: 0 0 5px;
        color: var(--text);
    }

    .achievement-section-title p {
        font-size: 13px;
        color: var(--muted);
        margin: 0;
    }

    .achievement-categories {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }

    .achievement-category {
        display: flex;
        gap: 16px;
        align-items: flex-start;
    }

    .category-icon {
        min-width: 48px;
        height: 48px;
        border-radius: 12px;
        background: #e9f7f1;
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .category-info {
        flex: 1;
    }

    .category-info h4 {
        font-size: 15px;
        margin: 2px 0 6px;
        color: var(--text);
    }

    .category-info p {
        font-size: 13px;
        color: var(--muted);
        margin: 0;
        line-height: 1.6;
    }

    .achievement-status {
        display: inline-block;
        margin-top: 12px;
        padding: 5px 10px;
        border-radius: 20px;
        background: #fff7e6;
        color: #b7791f;
        font-size: 11px;
        font-weight: 600;
    }

    .achievement-empty {
        text-align: center;
        padding: 45px 20px;
    }

    .achievement-empty .empty-icon {
        width: 65px;
        height: 65px;
        margin: 0 auto 15px;
        border-radius: 50%;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        font-size: 25px;
    }

    .achievement-empty h4 {
        font-size: 17px;
        margin-bottom: 8px;
        color: var(--text);
    }

    .achievement-empty p {
        font-size: 13px;
        color: var(--muted);
        max-width: 450px;
        margin: 0 auto;
        line-height: 1.7;
    }

    .achievement-notice {
        padding: 16px 18px;
        border-radius: 12px;
        background: #eff6ff;
        border: 1px solid #dbeafe;
        color: #1e40af;
        font-size: 13px;
        line-height: 1.7;
    }

    @media(max-width: 900px) {
        .achievement-stats {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media(max-width: 600px) {
        .achievement-stats,
        .achievement-categories {
            grid-template-columns: 1fr;
        }

        .achievement-card,
        .achievement-category {
            padding: 17px;
        }
    }
</style>

<div class="achievement-wrapper">

    {{-- Summary --}}
    <div class="achievement-stats">

        <div class="achievement-card">
            <div class="stat-icon">
                <i class="fa-solid fa-medal"></i>
            </div>
            <h3>{{ $summary['total'] }}</h3>
            <p>Total Achievements</p>
        </div>

        <div class="achievement-card">
            <div class="stat-icon">
                <i class="fa-solid fa-trophy"></i>
            </div>
            <h3>{{ $summary['completed'] }}</h3>
            <p>Completed</p>
        </div>

        <div class="achievement-card">
            <div class="stat-icon">
                <i class="fa-solid fa-spinner"></i>
            </div>
            <h3>{{ $summary['in_progress'] }}</h3>
            <p>In Progress</p>
        </div>

        <div class="achievement-card">
            <div class="stat-icon">
                <i class="fa-solid fa-lock"></i>
            </div>
            <h3>{{ $summary['locked'] }}</h3>
            <p>Locked</p>
        </div>

    </div>

    {{-- Achievement Categories --}}
    <div>
        <div class="achievement-section-title">
            <h3>Achievement Categories</h3>
            <p>Explore the available achievement categories.</p>
        </div>

        <div class="achievement-categories">

            @foreach($categories as $category)

                <div class="achievement-category">

                    <div class="category-icon">
                        <i class="fa-solid {{ $category['icon'] }}"></i>
                    </div>

                    <div class="category-info">

                        <h4>{{ $category['name'] }}</h4>

                        <p>{{ $category['description'] }}</p>

                        <span class="achievement-status">
                            <i class="fa-solid fa-clock"></i>
                            Not Configured
                        </span>

                    </div>

                </div>

            @endforeach

        </div>
    </div>

    {{-- Achievement History --}}
    <div>
        <div class="achievement-section-title">
            <h3>My Achievements</h3>
            <p>Your earned and ongoing achievements will appear here.</p>
        </div>

        <div class="achievement-empty">

            <div class="empty-icon">
                <i class="fa-solid fa-medal"></i>
            </div>

            <h4>No Achievements Available</h4>

            <p>
                You don't have any recorded achievements yet.
                Achievement criteria and reward policies will be
                available once configured by the platform.
            </p>

        </div>
    </div>

    {{-- Notice --}}
    <div class="achievement-notice">
        <i class="fa-solid fa-circle-info"></i>

        <strong> Important Notice:</strong>

        Achievement criteria, eligibility requirements and reward
        policies have not been configured yet. No financial reward
        or bonus is currently activated.
    </div>

</div>

@endsection