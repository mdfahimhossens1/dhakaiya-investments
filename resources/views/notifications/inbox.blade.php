@extends('layouts.app')

@section('title', 'Notifications')
@section('page-title', 'Notifications')

@section('content')

<div class="notifications-page">

    <div class="page-heading">
        <div>
            <div class="breadcrumb">
                <a href="{{ $routes['dashboard'] }}">Dashboard</a>
                <span>/</span>
                <span>Notifications</span>
            </div>

            <h1>Notifications</h1>
            <p>Stay updated with your account activities and important updates.</p>
        </div>

        @if($unreadCount > 0)
            <form action="{{ route($routes['read_all']) }}" method="POST">
                @csrf
                <button type="submit" class="mark-all-btn">✓ Mark All as Read</button>
            </form>
        @endif
    </div>

    <div class="notification-stats">
        <div class="notification-stat-card">
            <div class="stat-icon blue">♧</div>
            <div><span>Total Notifications</span><h2>{{ number_format($totalCount) }}</h2></div>
        </div>
        <div class="notification-stat-card">
            <div class="stat-icon orange">◉</div>
            <div><span>Unread Notifications</span><h2>{{ number_format($unreadCount) }}</h2></div>
        </div>
        <div class="notification-stat-card">
            <div class="stat-icon green">✓</div>
            <div><span>Read Notifications</span><h2>{{ number_format($totalCount - $unreadCount) }}</h2></div>
        </div>
    </div>

    @if(session('success'))
        <div class="success-message">✓ {{ session('success') }}</div>
    @endif

    <div class="notification-panel">

        <div class="notification-panel-heading">
            <div>
                <h3>All Notifications</h3>
                <p>Your recent account updates and announcements</p>
            </div>
            <span class="notification-count">{{ number_format($totalCount) }} Total</span>
        </div>

        @forelse($notifications as $notification)

            @php
                $data = $notification->data;
                $type = $data['type'] ?? 'info';

                $icon = match($type) {
                    'success' => '✓',
                    'warning' => '⚠',
                    'danger'  => '!',
                    default   => 'i',
                };

                $typeClass = in_array($type, ['success', 'warning', 'danger', 'info']) ? $type : 'info';
            @endphp

            <div class="notification-item {{ $notification->read_at ? 'is-read' : 'is-unread' }}">

                <div class="notification-icon {{ $typeClass }}">{{ $icon }}</div>

                <div class="notification-content">

                    <div class="notification-title-row">
                        <h4>{{ $data['title'] ?? 'Notification' }}</h4>
                        @if(!$notification->read_at)
                            <span class="unread-badge">New</span>
                        @endif
                    </div>

                    <p>{{ $data['message'] ?? 'You have received a new notification.' }}</p>

                    <div class="notification-meta">
                        <span>◷ {{ $notification->created_at->diffForHumans() }}</span>
                        <span>{{ $notification->created_at->format('d M, Y - h:i A') }}</span>
                    </div>

                    <div class="notification-actions">

                        @if(!$notification->read_at)
                            <form action="{{ route($routes['read'], $notification->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="read-btn">Mark as Read</button>
                            </form>
                        @endif

                        @if(!empty($data['url']))
                            <a href="{{ route($routes['open'], $notification->id) }}" class="view-notification-btn">
                                View Details →
                            </a>
                        @endif

                        <form action="{{ route($routes['destroy'], $notification->id) }}"
                              method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this notification?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-btn">Delete</button>
                        </form>

                    </div>
                </div>

                @if(!$notification->read_at)
                    <span class="unread-dot"></span>
                @endif

            </div>

        @empty

            <div class="empty-notifications">
                <div class="empty-notification-icon">♧</div>
                <h3>No Notifications Yet</h3>
                <p>You are all caught up! When there are new updates, they will appear here.</p>
            </div>

        @endforelse

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
    /* ===== apnar user notifications blade er style block ekdom same ===== */
</style>
@endpush