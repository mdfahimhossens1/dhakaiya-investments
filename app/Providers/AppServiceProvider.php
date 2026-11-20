<?php

namespace App\Providers;

use App\Models\User;
use App\Support\NotificationRoutes;
use App\Support\Phone;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::defaultView('vendor.pagination.simple-nav');
        Paginator::defaultSimpleView('vendor.pagination.simple-nav');

        // ফোন যেকোনো ফরম্যাটে সেভ থাকলেও মিলবে, শুধু Active ইউজার ঢুকবে
        Fortify::authenticateUsing(function (Request $request) {
            $variants = Phone::variants($request->input('phone'));

            if (! $variants) {
                return null;
            }

            foreach (User::whereIn('phone', $variants)->get() as $user) {
                if ($user->status === 'active' && Hash::check((string) $request->input('password'), $user->password)) {
                    return $user;
                }
            }

            return null;
        });

        // Header notification bell data
        View::composer('partials.notification-bell', function ($view) {
            $user = auth()->user();

            $data = ['bellUnread' => 0, 'bellItems' => collect(), 'bellRoutes' => null];

            if ($user) {
                try {
                    $data = [
                        'bellUnread' => $user->unreadNotifications()->count(),
                        'bellItems'  => $user->notifications()->latest()->limit(6)->get(),
                        'bellRoutes' => NotificationRoutes::for($user),
                    ];
                } catch (\Throwable $e) {
                    // notifications table ekhono migrate hoyni
                    report($e);
                }
            }

            $view->with($data);
        });
    }
}