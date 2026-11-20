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

    /** Bell khulle JS eta call kore, sob unread read hoye jay (JSON response). */
    public function markSeen()
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);

        return response()->json(['ok' => true]);
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

    /** Sob notification delete (clear all). */
    public function clear()
    {
        auth()->user()->notifications()->delete();

        return back()->with('success', 'All notifications cleared.');
    }

    /** Read mark kore link e pathay. */
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