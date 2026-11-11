@extends('layouts.app')

@section('page-title', 'Important Notices')

@section('content')

<style>
    .notice-page {
        padding: 5px 0 25px;
    }

    .notice-header {
        background: linear-gradient(135deg, #102b26, #087f5b);
        color: #fff;
        padding: 28px;
        border-radius: 14px;
        margin-bottom: 25px;
    }

    .notice-header h2 {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .notice-header p {
        color: rgba(255,255,255,.8);
        margin: 0;
        font-size: 14px;
    }

    .notice-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
        margin-bottom: 25px;
    }

    .notice-stat {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .notice-stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
    }

    .notice-stat-icon.total {
        background: #e8f1ff;
        color: #2563eb;
    }

    .notice-stat-icon.important {
        background: #fff1e8;
        color: #ea580c;
    }

    .notice-stat-icon.archived {
        background: #f1f5f9;
        color: #64748b;
    }

    .notice-stat h3 {
        font-size: 22px;
        font-weight: 700;
        margin: 0;
        color: var(--text);
    }

    .notice-stat span {
        color: var(--muted);
        font-size: 13px;
    }

    .notice-section {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
    }

    .notice-section-header {
        padding: 20px 22px;
        border-bottom: 1px solid var(--border);
    }

    .notice-section-header h4 {
        font-size: 17px;
        font-weight: 700;
        margin: 0;
        color: var(--text);
    }

    .notice-card {
        padding: 22px;
        border-bottom: 1px solid var(--border);
        display: flex;
        gap: 16px;
    }

    .notice-card:last-child {
        border-bottom: none;
    }

    .notice-icon {
        min-width: 46px;
        height: 46px;
        border-radius: 12px;
        background: #fff1e8;
        color: #ea580c;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
    }

    .notice-content {
        flex: 1;
        min-width: 0;
    }

    .notice-content h5 {
        font-size: 15px;
        font-weight: 700;
        color: var(--text);
        margin: 0 0 8px;
    }

    .notice-content p {
        color: #64748b;
        font-size: 14px;
        line-height: 1.7;
        margin: 0 0 12px;
    }

    .notice-meta {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        font-size: 12px;
        color: var(--muted);
    }

    .notice-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        background: #fff1e8;
        color: #c2410c;
    }

    .notice-empty {
        padding: 55px 20px;
        text-align: center;
    }

    .notice-empty i {
        font-size: 42px;
        color: #cbd5e1;
        margin-bottom: 15px;
    }

    .notice-empty h5 {
        color: var(--text);
        font-weight: 700;
        margin-bottom: 8px;
    }

    .notice-empty p {
        color: var(--muted);
        font-size: 14px;
        margin: 0;
    }

    .notice-info {
        background: #eff6ff;
        border: 1px solid #dbeafe;
        border-radius: 10px;
        padding: 15px 18px;
        margin-top: 20px;
        display: flex;
        gap: 12px;
        color: #1d4ed8;
        font-size: 13px;
        line-height: 1.6;
    }

    @media(max-width: 768px) {
        .notice-stats {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .notice-header {
            padding: 22px;
        }

        .notice-header h2 {
            font-size: 21px;
        }

        .notice-card {
            padding: 17px;
        }
    }
</style>

<div class="notice-page">

    {{-- Page Header --}}
    <div class="notice-header">
        <h2>
            <i class="fa-solid fa-circle-exclamation me-2"></i>
            Important Notices
        </h2>

        <p>
            Stay informed about important platform updates and announcements.
        </p>
    </div>

    {{-- Statistics --}}
    <div class="notice-stats">

        <div class="notice-stat">
            <div class="notice-stat-icon total">
                <i class="fa-solid fa-bell"></i>
            </div>

            <div>
                <h3>{{ $summary['total'] ?? 0 }}</h3>
                <span>Total Notices</span>
            </div>
        </div>

        <div class="notice-stat">
            <div class="notice-stat-icon important">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>

            <div>
                <h3>{{ $summary['important'] ?? 0 }}</h3>
                <span>Important Notices</span>
            </div>
        </div>

        <div class="notice-stat">
            <div class="notice-stat-icon archived">
                <i class="fa-solid fa-box-archive"></i>
            </div>

            <div>
                <h3>{{ $summary['archived'] ?? 0 }}</h3>
                <span>Archived Notices</span>
            </div>
        </div>

    </div>

    {{-- Notices List --}}
    <div class="notice-section">

        <div class="notice-section-header">
            <h4>
                <i class="fa-solid fa-list-ul me-2"></i>
                Important Information
            </h4>
        </div>

        @forelse($notices as $notice)

            <div class="notice-card">

                <div class="notice-icon">
                    <i class="fa-solid fa-circle-exclamation"></i>
                </div>

                <div class="notice-content">

                    <div class="d-flex align-items-center flex-wrap gap-2 mb-2">

                        <h5>
                            {{ data_get($notice, 'title', 'Important Notice') }}
                        </h5>

                        <span class="notice-badge">
                            {{ ucfirst(data_get($notice, 'priority', 'Important')) }}
                        </span>

                    </div>

                    <p>
                        {{ data_get($notice, 'message', 'No additional information available.') }}
                    </p>

                    <div class="notice-meta">

                        @if(data_get($notice, 'category'))
                            <span>
                                <i class="fa-solid fa-folder me-1"></i>
                                {{ data_get($notice, 'category') }}
                            </span>
                        @endif

                        @if(data_get($notice, 'published_at'))
                            <span>
                                <i class="fa-regular fa-calendar me-1"></i>
                                {{ \Carbon\Carbon::parse(data_get($notice, 'published_at'))->format('d M Y') }}
                            </span>
                        @elseif(data_get($notice, 'created_at'))
                            <span>
                                <i class="fa-regular fa-calendar me-1"></i>
                                {{ \Carbon\Carbon::parse(data_get($notice, 'created_at'))->format('d M Y') }}
                            </span>
                        @endif

                    </div>

                </div>

            </div>

        @empty

            <div class="notice-empty">

                <i class="fa-solid fa-clipboard-check"></i>

                <h5>No Important Notices</h5>

                <p>
                    There are currently no important notices available.
                    Please check again later.
                </p>

            </div>

        @endforelse

    </div>

    {{-- Information --}}
    <div class="notice-info">
        <i class="fa-solid fa-circle-info mt-1"></i>

        <div>
            <strong>Information:</strong>
            Important notices are intended to communicate platform updates,
            policy information and general announcements. Please review
            official notices carefully.
        </div>
    </div>

</div>

@endsection