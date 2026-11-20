<?php

namespace App\Support;

use Illuminate\Support\Facades\Route;

class NotificationRoutes
{
    public static function for($user): array
    {
        $role = $user->role ?? 'user';

        [$prefix, $dashboard] = match ($role) {
            'agent'                => ['agent.notifications.', 'agent.dashboard'],
            'admin', 'super_admin' => ['super-admin.inbox.', 'super-admin.dashboard'],
            default                => ['user.notifications.', 'user.dashboard'],
        };

        return [
            'index'     => $prefix . 'index',
            'seen'      => $prefix . 'seen',
            'read'      => $prefix . 'read',
            'read_all'  => $prefix . 'read-all',
            'clear'     => $prefix . 'clear',
            'open'      => $prefix . 'open',
            'destroy'   => $prefix . 'destroy',
            'dashboard' => Route::has($dashboard) ? route($dashboard) : url('/'),
        ];
    }
}