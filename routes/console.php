<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('profit:distribute')
    ->dailyAt('00:05')
    ->timezone('Asia/Dhaka')
    ->withoutOverlapping(120)
    ->onOneServer()
    ->appendOutputTo(storage_path('logs/profit.log'));
Schedule::command('referral:commission')
    ->monthlyOn(1, '01:00')
    ->timezone('Asia/Dhaka')
    ->withoutOverlapping(120)
    ->onOneServer()
    ->appendOutputTo(storage_path('logs/referral.log'));

Schedule::command('referral:release')
    ->dailyAt('00:30')
    ->timezone('Asia/Dhaka')
    ->withoutOverlapping(60)
    ->onOneServer()
    ->appendOutputTo(storage_path('logs/referral.log'));
Schedule::command('position:process')
    ->dailyAt('00:45')
    ->timezone('Asia/Dhaka')
    ->withoutOverlapping(120)
    ->onOneServer()
    ->appendOutputTo(storage_path('logs/position.log'));

Schedule::command('leaderboard:reward')
    ->monthlyOn(1, '02:00')
    ->timezone('Asia/Dhaka')
    ->withoutOverlapping(120)
    ->onOneServer()
    ->appendOutputTo(storage_path('logs/position.log'));
Schedule::command('referral:release')->everyTenMinutes();