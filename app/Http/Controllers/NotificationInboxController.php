<?php

namespace App\Http\Controllers;

use App\Support\NotificationRoutes;

class NotificationInboxController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $notifications = $user->notifications()->latest()->paginate(10);
        $unreadCount   = $user->unreadNotifications()->count();
        $totalCount    = $user->notifications()->count();
        $routes        = NotificationRoutes::for($user);

        return view('notifications.inbox', compact(
            'notifications', 'unreadCount', 'totalCount', 'routes'
        ));
    }

    public function markAsRead(string $id)
    {
        auth()->user()->notifications()->where('id', $id)->firstOrFail()->markAsRead();

        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);

        return back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Mark as read, then go to the notification link.
     */
    public function open(string $id)
    {
        $user = auth()->user();

        $notification = $user->notifications()->where('id', $id)->firstOrFail();
        $notification->markAsRead();

        $url = $notification->data['url'] ?? null;

        if ($url && preg_match('#^(/(?!/)|https?://)#', $url)) {
            return redirect($url);
        }

        return redirect()->route(NotificationRoutes::for($user)['index']);
    }

    public function destroy(string $id)
    {
        auth()->user()->notifications()->where('id', $id)->firstOrFail()->delete();

        return back()->with('success', 'Notification deleted successfully.');
    }
}