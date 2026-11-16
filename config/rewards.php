<?php

return [
    'position' => [
        'min_lock_days' => (int) env('POSITION_MIN_LOCK_DAYS', 30),
        'auto_lock_on_buy' => (bool) env('POSITION_AUTO_LOCK', false),
    ],

    'resell' => [
        // rank => reward (BDT). ক্লায়েন্টের আসল অঙ্ক বসান
        'top' => [1 => 5000, 2 => 3000, 3 => 1000],
        'min_volume_bdt' => 1000,   // এর কম রিসেল করলে Leaderboard reward নেই
        'wash_days' => 7,
    ],
];