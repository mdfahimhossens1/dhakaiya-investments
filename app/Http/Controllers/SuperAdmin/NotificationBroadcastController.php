<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\NotificationBroadcast;
use App\Models\User;
use App\Notifications\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Schema;

class NotificationBroadcastController extends Controller
{
    public function index()
    {
        $broadcasts = NotificationBroadcast::with(['sender:id,name', 'target:id,name,email'])
            ->latest()->paginate(15);

        $stats = [
            'broadcasts' => NotificationBroadcast::count(),
            'deliveries' => (int) NotificationBroadcast::sum('recipients_count'),
            'today'      => NotificationBroadcast::whereDate('created_at', today())->count(),
            'last'       => NotificationBroadcast::latest()->first()?->created_at,
        ];

        $audienceCounts = [
            'all_users'  => User::where('role', 'user')->count(),
            'all_admins' => User::whereIn('role', ['admin', 'super_admin'])->count(),
            'everyone'   => User::count(),
        ];

        $inboxReady = Schema::hasTable('notifications');

        return view('super-admin.notifications.index', compact('broadcasts', 'stats', 'audienceCounts', 'inboxReady'));
    }

    public function store(Request $request)
    {
        if (! Schema::hasTable('notifications')) {
            return back()->withInput()->withErrors([
                'notifications' => 'The "notifications" table is missing. Run: php artisan make:notifications-table (or notifications:table on older Laravel), then php artisan migrate.',
            ]);
        }

        $data = $request->validate([
            'title'        => 'required|string|max:120',
            'message'      => 'required|string|max:1000',
            'type'         => 'required|in:' . implode(',', array_keys(NotificationBroadcast::TYPES)),
            'audience'     => 'required|in:' . implode(',', array_keys(NotificationBroadcast::AUDIENCES)),
            'target_email' => 'required_if:audience,specific|nullable|email|exists:users,email',
            'action_url'   => ['nullable', 'string', 'max:255', 'regex:/^(\/|https?:\/\/)/'],
        ], [
            'action_url.regex'         => 'The link must start with / (internal page) or http(s)://',
            'target_email.exists'      => 'No user found with that email address.',
            'target_email.required_if' => 'Enter the email of the user you want to notify.',
        ]);

        $recipients = match ($data['audience']) {
            'all_users'  => User::where('role', 'user'),
            'all_admins' => User::whereIn('role', ['admin', 'super_admin']),
            'everyone'   => User::query(),
            'specific'   => User::where('email', $data['target_email']),
        };

        $count = (clone $recipients)->count();

        if ($count === 0) {
            return back()->withInput()->withErrors(['audience' => 'There is nobody in that audience yet.']);
        }

        $broadcast = NotificationBroadcast::create([
            'title'            => $data['title'],
            'message'          => $data['message'],
            'type'             => $data['type'],
            'audience'         => $data['audience'],
            'target_user_id'   => $data['audience'] === 'specific' ? User::where('email', $data['target_email'])->value('id') : null,
            'action_url'       => $data['action_url'] ?? null,
            'recipients_count' => $count,
            'sent_by'          => auth()->id(),
        ]);

        $notification = new AdminNotification(
            $data['title'], $data['message'], $data['type'], $data['action_url'] ?? null, $broadcast->id
        );

        $recipients->chunkById(200, fn ($users) => Notification::send($users, $notification));

        return redirect()->route('super-admin.notifications.index')
            ->with('success', "Notification sent to {$count} " . ($count === 1 ? 'user.' : 'users.'));
    }

    public function destroy(NotificationBroadcast $broadcast)
    {
        $broadcast->delete();

        return redirect()->route('super-admin.notifications.index')
            ->with('success', 'Broadcast removed from history. Copies already delivered stay in users\' inboxes.');
    }
}
