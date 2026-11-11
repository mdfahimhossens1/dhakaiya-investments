<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display user notifications.
     */
    public function index()
    {
        $user = auth()->user();

        $notifications = $user->notifications()
            ->latest()
            ->paginate(10);

        $unreadCount = $user->unreadNotifications()->count();

        $totalCount = $user->notifications()->count();

        return view('user.notifications.index', compact(
            'notifications',
            'unreadCount',
            'totalCount'
        ));
    }


    /**
     * Mark a notification as read.
     */
    public function markAsRead(string $id)
    {
        $notification = auth()->user()
            ->notifications()
            ->where('id', $id)
            ->firstOrFail();

        $notification->markAsRead();

        return back()->with('success', 'Notification marked as read.');
    }


    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        auth()->user()
            ->unreadNotifications()
            ->update([
                'read_at' => now(),
            ]);

        return back()->with('success', 'All notifications marked as read.');
    }


    /**
     * Delete a notification.
     */
    public function destroy(string $id)
    {
        auth()->user()
            ->notifications()
            ->where('id', $id)
            ->firstOrFail()
            ->delete();

        return back()->with('success', 'Notification deleted successfully.');
    }

    /**
 * Display user announcements.
 */
public function announcements()
{
    $user = auth()->user();

    $announcements = collect([]);

    $summary = [
        'total' => 0,
        'active' => 0,
        'archived' => 0,
    ];

    return view('user.notifications.announcements', compact(
        'user',
        'announcements',
        'summary'
    ));
}

/**
 * Display important notices.
 */
public function importantNotices()
{
    $user = auth()->user();

    $notices = collect([]);

    $summary = [
        'total' => 0,
        'important' => 0,
        'archived' => 0,
    ];

    return view('user.notifications.important-notices', compact(
        'user',
        'notices',
        'summary'
    ));
}

}