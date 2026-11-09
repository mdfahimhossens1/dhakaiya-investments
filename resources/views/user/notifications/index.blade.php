@extends('layouts.app')

@section('page-title', 'Notifications')

@section('content')

<div class="notifications-page">

    {{-- Page Heading --}}
    <div class="page-heading">

        <div>
            <div class="breadcrumb">
                <a href="{{ route('user.dashboard') }}">Dashboard</a>
                <span>/</span>
                <span>Notifications</span>
            </div>

            <h1>Notifications</h1>

            <p>Stay updated with your account activities and important updates.</p>
        </div>

        @if($unreadCount > 0)
            <form action="{{ route('user.notifications.read-all') }}" method="POST">
                @csrf

                <button type="submit" class="mark-all-btn">
                    ✓ Mark All as Read
                </button>
            </form>
        @endif

    </div>


    {{-- Summary Cards --}}
    <div class="notification-stats">

        <div class="notification-stat-card">
            <div class="stat-icon blue">♧</div>

            <div>
                <span>Total Notifications</span>
                <h2>{{ number_format($totalCount) }}</h2>
            </div>
        </div>


        <div class="notification-stat-card">
            <div class="stat-icon orange">◉</div>

            <div>
                <span>Unread Notifications</span>
                <h2>{{ number_format($unreadCount) }}</h2>
            </div>
        </div>


        <div class="notification-stat-card">
            <div class="stat-icon green">✓</div>

            <div>
                <span>Read Notifications</span>
                <h2>{{ number_format($totalCount - $unreadCount) }}</h2>
            </div>
        </div>

    </div>


    {{-- Success Message --}}
    @if(session('success'))
        <div class="success-message">
            ✓ {{ session('success') }}
        </div>
    @endif


    {{-- Notification List --}}
    <div class="notification-panel">

        <div class="notification-panel-heading">

            <div>
                <h3>All Notifications</h3>
                <p>Your recent account updates and announcements</p>
            </div>

            <span class="notification-count">
                {{ number_format($totalCount) }} Total
            </span>

        </div>


        @forelse($notifications as $notification)

            @php
                $data = $notification->data;

                $type = $data['type'] ?? 'info';

                $icon = match($type) {
                    'success' => '✓',
                    'warning' => '⚠',
                    'danger' => '!',
                    'info' => 'i',
                    default => '♧',
                };

                $typeClass = in_array($type, ['success', 'warning', 'danger', 'info'])
                    ? $type
                    : 'info';
            @endphp


            <div class="notification-item {{ $notification->read_at ? 'is-read' : 'is-unread' }}">

                <div class="notification-icon {{ $typeClass }}">
                    {{ $icon }}
                </div>


                <div class="notification-content">

                    <div class="notification-title-row">

                        <h4>
                            {{ $data['title'] ?? 'Notification' }}
                        </h4>

                        @if(!$notification->read_at)
                            <span class="unread-badge">New</span>
                        @endif

                    </div>


                    <p>
                        {{ $data['message'] ?? 'You have received a new notification.' }}
                    </p>


                    <div class="notification-meta">

                        <span>
                            ◷ {{ $notification->created_at->diffForHumans() }}
                        </span>

                        <span>
                            {{ $notification->created_at->format('d M, Y - h:i A') }}
                        </span>

                    </div>


                    <div class="notification-actions">

                        @if(!$notification->read_at)

                            <form action="{{ route('user.notifications.read', $notification->id) }}"
                                  method="POST">

                                @csrf

                                <button type="submit" class="read-btn">
                                    Mark as Read
                                </button>

                            </form>

                        @endif


                        @if(!empty($data['url']) && str_starts_with($data['url'], '/'))
                            <a href="{{ $data['url'] }}" class="view-notification-btn">
                                View Details →
                            </a>
                        @endif


                        <form action="{{ route('user.notifications.destroy', $notification->id) }}"
                              method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this notification?')">

                            @csrf
                            @method('DELETE')

                            <button type="submit" class="delete-btn">
                                Delete
                            </button>

                        </form>

                    </div>

                </div>


                @if(!$notification->read_at)
                    <span class="unread-dot"></span>
                @endif

            </div>

        @empty

            <div class="empty-notifications">

                <div class="empty-notification-icon">
                    ♧
                </div>

                <h3>No Notifications Yet</h3>

                <p>
                    You are all caught up! When there are new updates,
                    they will appear here.
                </p>

            </div>

        @endforelse


        {{-- Pagination --}}
        @if($notifications->hasPages())

            <div class="notification-pagination">
                {{ $notifications->links() }}
            </div>

        @endif

    </div>

</div>

@endsection


@push('styles')

<style>

    .notifications-page {
        width: 100%;
    }

    /* Heading */

    .page-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-bottom: 24px;
    }

    .breadcrumb {
        display: flex;
        gap: 9px;
        font-size: 11px;
        color: #94a3b8;
        margin-bottom: 9px;
    }

    .breadcrumb a {
        color: var(--primary);
        text-decoration: none;
    }

    .page-heading h1 {
        font-size: 23px;
        font-weight: 750;
        color: #1e293b;
        margin: 0;
    }

    .page-heading p {
        color: #84919f;
        font-size: 12px;
        margin-top: 6px;
    }

    .mark-all-btn {
        border: 1px solid #d1e7df;
        background: #ecfdf5;
        color: var(--primary);
        padding: 11px 15px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 650;
        cursor: pointer;
    }

    .mark-all-btn:hover {
        background: var(--primary);
        color: #fff;
    }


    /* Stats */

    .notification-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 22px;
    }

    .notification-stat-card {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 20px;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 11px;
    }

    .stat-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: grid;
        place-items: center;
        font-size: 18px;
        font-weight: 700;
    }

    .stat-icon.blue {
        background: #dbeafe;
        color: #2563eb;
    }

    .stat-icon.orange {
        background: #ffedd5;
        color: #ea580c;
    }

    .stat-icon.green {
        background: #dcfce7;
        color: #15803d;
    }

    .notification-stat-card span {
        font-size: 11px;
        color: #94a3b8;
    }

    .notification-stat-card h2 {
        font-size: 21px;
        color: #1e293b;
        margin-top: 5px;
    }


    /* Success */

    .success-message {
        background: #ecfdf5;
        color: #15803d;
        border: 1px solid #bbf7d0;
        padding: 12px 15px;
        border-radius: 8px;
        font-size: 12px;
        margin-bottom: 18px;
    }


    /* Notification Panel */

    .notification-panel {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
    }

    .notification-panel-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 22px;
        border-bottom: 1px solid #f1f5f9;
    }

    .notification-panel-heading h3 {
        font-size: 14px;
        color: #1e293b;
        margin-bottom: 5px;
    }

    .notification-panel-heading p {
        font-size: 11px;
        color: #94a3b8;
    }

    .notification-count {
        background: #f1f5f9;
        color: #64748b;
        padding: 7px 11px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 650;
    }


    /* Notification Item */

    .notification-item {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        padding: 20px 22px;
        border-bottom: 1px solid #f1f5f9;
        position: relative;
        transition: .2s;
    }

    .notification-item:hover {
        background: #fafdfb;
    }

    .notification-item.is-unread {
        background: #f8fffb;
    }

    .notification-icon {
        width: 42px;
        height: 42px;
        flex-shrink: 0;
        display: grid;
        place-items: center;
        border-radius: 10px;
        font-size: 17px;
        font-weight: 700;
    }

    .notification-icon.success {
        background: #dcfce7;
        color: #15803d;
    }

    .notification-icon.warning {
        background: #ffedd5;
        color: #ea580c;
    }

    .notification-icon.danger {
        background: #fee2e2;
        color: #dc2626;
    }

    .notification-icon.info {
        background: #dbeafe;
        color: #2563eb;
    }

    .notification-content {
        flex: 1;
        min-width: 0;
    }

    .notification-title-row {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .notification-title-row h4 {
        font-size: 13px;
        font-weight: 700;
        color: #334155;
    }

    .unread-badge {
        background: #dcfce7;
        color: #15803d;
        padding: 3px 7px;
        border-radius: 10px;
        font-size: 9px;
        font-weight: 700;
    }

    .notification-content > p {
        font-size: 11px;
        color: #64748b;
        line-height: 1.7;
        margin: 7px 0;
    }

    .notification-meta {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        color: #94a3b8;
        font-size: 10px;
        margin-top: 10px;
    }

    .notification-actions {
        display: flex;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
        margin-top: 12px;
    }

    .notification-actions button,
    .notification-actions a {
        border: 0;
        background: transparent;
        font-size: 10px;
        font-weight: 650;
        cursor: pointer;
        text-decoration: none;
        padding: 0;
    }

    .read-btn,
    .view-notification-btn {
        color: var(--primary);
    }

    .delete-btn {
        color: #e11d48;
    }

    .unread-dot {
        width: 8px;
        height: 8px;
        background: #16a34a;
        border-radius: 50%;
        position: absolute;
        top: 25px;
        right: 20px;
    }


    /* Empty State */

    .empty-notifications {
        text-align: center;
        padding: 65px 20px;
    }

    .empty-notification-icon {
        width: 65px;
        height: 65px;
        display: grid;
        place-items: center;
        margin: 0 auto 16px;
        border-radius: 50%;
        background: #ecfdf5;
        color: var(--primary);
        font-size: 26px;
    }

    .empty-notifications h3 {
        color: #334155;
        font-size: 15px;
        margin-bottom: 8px;
    }

    .empty-notifications p {
        color: #94a3b8;
        font-size: 11px;
        line-height: 1.7;
        max-width: 340px;
        margin: auto;
    }


    /* Pagination */

    .notification-pagination {
        padding: 18px 22px;
        border-top: 1px solid #f1f5f9;
    }


    /* Responsive */

    @media(max-width: 768px) {

        .notification-stats {
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .page-heading {
            align-items: flex-start;
            flex-direction: column;
        }

        .notification-panel-heading {
            padding: 16px;
        }

        .notification-item {
            padding: 17px 15px;
        }

        .notification-meta {
            flex-direction: column;
            gap: 6px;
        }
    }

</style>

@endpush