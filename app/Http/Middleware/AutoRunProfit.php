<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class AutoRunProfit
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (config('exchange.profit_autorun') && now()->format('H:i') >= '00:10') {
            $key = 'profit-autorun:' . today()->toDateString();

            if (Cache::add($key, 1, now()->endOfDay())) {
                app()->terminating(function () use ($key) {
                    if (Artisan::call('profit:distribute') !== 0) {
                        Cache::forget($key);   // ব্যর্থ হলে পরের ভিজিটে আবার চেষ্টা
                    }
                });
            }
        }

        return $response;
    }
}