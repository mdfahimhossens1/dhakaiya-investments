@extends('layouts.app')

@section('page-title', 'Announcements')

@section('content')

<style>
    .announcements-page {
        width: 100%;
    }

    .announcement-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .announcement-header h1 {
        font-size: 23px;
        font-weight: 750;
        color: #1e293b;
        margin: 0 0 7px;
    }

    .announcement-header p {
        color: #84919f;
        font-size: 12px;
        margin: 0;
    }

    .announcement-hero {
        background: linear-gradient(135deg, #087f5b, #065f46);
        color: #fff;
        border-radius: 13px;
        padding: 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 24px;
    }

    .announcement-hero h2 {
        font-size: 19px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .announcement-hero p {
        font-size: 12px;
        opacity: .85;
        margin: 0;
        line-height: 1.7;
    }

    .announcement-hero-icon {
        width: 58px;
        height: 58px;
        background: rgba(255,255,255,.15);
        border-radius: 13px;
        display: grid;
        place-items: center;
        font-size: 25px;
        flex-shrink: 0;
    }

    .announcement-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .announcement-stat {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 11px;
        padding: 19px;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .announcement-stat-icon {
        width: 43px;
        height: 43px;
        border-radius: 10px;
        display: grid;
        place-items: center;
        font-size: 18px;
    }

    .announcement-stat-icon.blue {
        background: #dbeafe;
        color: #2563eb;
    }

    .announcement-stat-icon.green {
        background: #dcfce7;
        color: #15803d;
    }

    .announcement-stat-icon.orange {
        background: #ffedd5;
        color: #ea580c;
    }

    .announcement-stat span {
        font-size: 11px;
        color: #94a3b8;
    }

    .announcement-stat h3 {
        font-size: 21px;
        color: #1e293b;
        margin: 5px 0 0;
        font-weight: 750;
    }

    .announcement-panel {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
    }

    .announcement-panel-heading {
        padding: 21px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
    }

    .announcement-panel-heading h3 {
        font-size: 14px;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 5px;
    }

    .announcement-panel-heading p {
        font-size: 11px;
        color: #94a3b8;
        margin: 0;
    }

    .announcement-count {
        background: #ecfdf5;
        color: #087f5b;
        padding: 7px 12px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .announcement-list {
        padding: 20px;
    }

    .announcement-item {
        display: flex;
        gap: 15px;
        padding: 19px;
        border: 1px solid var(--border);
        border-radius: 10px;
        margin-bottom: 15px;
        transition: .2s;
    }

    .announcement-item:last-child {
        margin-bottom: 0;
    }

    .announcement-item:hover {
        border-color: var(--primary);
        background: #fafdfb;
    }

    .announcement-item-icon {
        width: 43px;
        height: 43px;
        flex-shrink: 0;
        display: grid;
        place-items: center;
        border-radius: 10px;
        background: #e8f5ef;
        color: var(--primary);
        font-size: 18px;
    }

    .announcement-item-content {
        flex: 1;
        min-width: 0;
    }

    .announcement-item-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 8px;
    }

    .announcement-item h4 {
        font-size: 14px;
        color: #334155;
        font-weight: 700;
        margin: 0;
    }

    .announcement-badge {
        background: #ecfdf5;
        color: #087f5b;
        font-size: 9px;
        font-weight: 700;
        padding: 5px 9px;
        border-radius: 15px;
    }

    .announcement-item-content p {
        font-size: 12px;
        color: #64748b;
        line-height: 1.7;
        margin: 0 0 10px;
    }

    .announcement-meta {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        font-size: 10px;
        color: #94a3b8;
    }

    .announcement-empty {
        text-align: center;
        padding: 65px 20px;
    }

    .announcement-empty-icon {
        width: 65px;
        height: 65px;
        margin: 0 auto 16px;
        border-radius: 50%;
        background: #ecfdf5;
        color: var(--primary);
        display: grid;
        place-items: center;
        font-size: 26px;
    }

    .announcement-empty h3 {
        font-size: 15px;
        color: #334155;
        margin-bottom: 8px;
    }

    .announcement-empty p {
        font-size: 11px;
        color: #94a3b8;
        line-height: 1.7;
        max-width: 350px;
        margin: auto;
    }

    .announcement-notice {
        display: flex;
        gap: 12px;
        padding: 16px;
        margin-top: 20px;
        background: #eff6ff;
        border: 1px solid #dbeafe;
        border-radius: 10px;
    }

    .announcement-notice i {
        color: #2563eb;
        font-size: 17px;
        margin-top: 2px;
    }

    .announcement-notice h4 {
        font-size: 12px;
        font-weight: 700;
        color: #1e40af;
        margin: 0 0 5px;
    }

    .announcement-notice p {
        font-size: 11px;
        color: #475569;
        line-height: 1.7;
        margin: 0;
    }

    @media(max-width: 768px) {
        .announcement-stats {
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .announcement-header {
            align-items: flex-start;
        }

        .announcement-hero {
            padding: 20px;
        }

        .announcement-list {
            padding: 12px;
        }
    }

    @media(max-width: 480px) {
        .announcement-item {
            padding: 14px;
            gap: 10px;
        }

        .announcement-item-icon {
            width: 36px;
            height: 36px;
        }

        .announcement-panel-heading {
            padding: 15px;
        }
    }
</style>

<div class="announcements-page">

    {{-- Page Header --}}
    <div class="announcement-header">
        <div>
            <h1>Announcements</h1>
            <p>Stay informed about the latest platform updates and announcements.</p>
        </div>
    </div>

    {{-- Hero --}}
    <div class="announcement-hero">
        <div>
            <h2>Platform Announcements</h2>
            <p>
                Find the latest news, updates and general information
                related to the platform.
            </p>
        </div>

        <div class="announcement-hero-icon">
            <i class="fa-solid fa-bullhorn"></i>
        </div>
    </div>

    {{-- Statistics --}}
    <div class="announcement-stats">

        <div class="announcement-stat">
            <div class="announcement-stat-icon blue">
                <i class="fa-solid fa-bullhorn"></i>
            </div>

            <div>
                <span>Total Announcements</span>
                <h3>{{ number_format($summary['total']) }}</h3>
            </div>
        </div>

        <div class="announcement-stat">
            <div class="announcement-stat-icon green">
                <i class="fa-solid fa-circle-check"></i>
            </div>

            <div>
                <span>Active Announcements</span>
                <h3>{{ number_format($summary['active']) }}</h3>
            </div>
        </div>

        <div class="announcement-stat">
            <div class="announcement-stat-icon orange">
                <i class="fa-solid fa-box-archive"></i>
            </div>

            <div>
                <span>Archived Announcements</span>
                <h3>{{ number_format($summary['archived']) }}</h3>
            </div>
        </div>

    </div>

    {{-- Announcement List --}}
    <div class="announcement-panel">

        <div class="announcement-panel-heading">
            <div>
                <h3>All Announcements</h3>
                <p>Latest platform news and updates</p>
            </div>

            <span class="announcement-count">
                {{ number_format($summary['total']) }} Total
            </span>
        </div>

        @forelse($announcements as $announcement)

            <div class="announcement-list">

                <div class="announcement-item">

                    <div class="announcement-item-icon">
                        <i class="fa-solid fa-bullhorn"></i>
                    </div>

                    <div class="announcement-item-content">

                        <div class="announcement-item-top">
                            <h4>
                                {{ $announcement->title }}
                            </h4>

                            <span class="announcement-badge">
                                {{ ucfirst($announcement->status ?? 'Active') }}
                            </span>
                        </div>

                        <p>
                            {{ $announcement->description ?? $announcement->message ?? '' }}
                        </p>

                        <div class="announcement-meta">
                            <span>
                                <i class="fa-regular fa-calendar"></i>
                                {{ $announcement->created_at?->format('d M, Y') ?? 'Date unavailable' }}
                            </span>
                        </div>

                    </div>

                </div>

            </div>

        @empty

            <div class="announcement-empty">

                <div class="announcement-empty-icon">
                    <i class="fa-solid fa-bullhorn"></i>
                </div>

                <h3>No Announcements Yet</h3>

                <p>
                    There are currently no announcements available.
                    New platform updates will appear here when published.
                </p>

            </div>

        @endforelse

    </div>

    {{-- Information Notice --}}
    <div class="announcement-notice">

        <i class="fa-solid fa-circle-info"></i>

        <div>
            <h4>Stay Updated</h4>

            <p>
                Please check this section regularly to stay informed
                about platform news, updates and general announcements.
            </p>
        </div>

    </div>

</div>

@endsection