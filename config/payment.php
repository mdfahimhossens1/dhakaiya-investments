<?php

return [
    'methods' => [
        'bkash'  => ['label' => 'bKash',  'type' => 'mobile', 'number' => env('PAY_BKASH_NUMBER'),  'currencies' => ['BDT']],
        'nagad'  => ['label' => 'Nagad',  'type' => 'mobile', 'number' => env('PAY_NAGAD_NUMBER'),  'currencies' => ['BDT']],
        'rocket' => ['label' => 'Rocket', 'type' => 'mobile', 'number' => env('PAY_ROCKET_NUMBER'), 'currencies' => ['BDT']],
        'bank'   => ['label' => 'Bank Transfer', 'type' => 'bank', 'number' => env('PAY_BANK_DETAILS'), 'currencies' => ['*']],
    ],

    'deposit' => [
        'min' => ['default' => 1, 'BDT' => 100, 'USD' => 1],
        'max_pending' => 5,
    ],

    'withdraw' => [
        'min' => ['default' => 1, 'BDT' => 500, 'USD' => 5],
        'interval_days' => 10,
        'max_pending' => 3,
    ],
    
];