<?php

namespace App\Support;

use App\Models\User;
use App\Notifications\AdminNotification;
use Illuminate\Support\Facades\Notification;

class Notify
{
    /** Ekjon user ke notification pathay. Fail korleo main kaj atkay na. */
    public static function user($user, string $title, string $message, string $type = 'info', ?string $url = null): void
    {
        if (! $user) {
            return;
        }

        try {
            $user->notify(new AdminNotification($title, $message, $type, $url));
        } catch (\Throwable $e) {
            report($e);
        }
    }

    /** Sob admin / super admin ke pathay (jemon notun deposit request ashle). */
    public static function admins(string $title, string $message, string $type = 'info', ?string $url = null): void
    {
        try {
            $admins = User::whereIn('role', ['admin', 'super_admin'])->get();

            if ($admins->isNotEmpty()) {
                Notification::send($admins, new AdminNotification($title, $message, $type, $url));
            }
        } catch (\Throwable $e) {
            report($e);
        }
    }

    public static function money($amount, ?string $currency = 'BDT'): string
    {
        return number_format((float) $amount, 2) . ' ' . strtoupper((string) $currency);
    }
}