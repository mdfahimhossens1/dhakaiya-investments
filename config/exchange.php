<?php

$codes = [
    'USD', 'INR', 'GBP', 'CAD', 'AUD', 'NZD', 'SGD', 'MYR', 'PKR', 'LKR', 'NPR',
    'SAR', 'AED', 'QAR', 'KWD', 'BHD', 'OMR', 'JOD', 'EGP', 'EUR', 'CHF', 'SEK',
    'NOK', 'DKK', 'PLN', 'CZK', 'TRY', 'RUB', 'UAH', 'JPY', 'CNY', 'HKD', 'KRW',
    'THB', 'IDR', 'PHP', 'VND', 'BRL', 'MXN', 'ZAR', 'NGN', 'KES', 'GHS',
];

return [
    // 1 unit = কত BDT। .env-এ RATE_INR_BDT=... এভাবে বসান। খালি থাকলে ওই কারেন্সি বন্ধ।
    'rates' => ['BDT' => 1] + collect($codes)
        ->mapWithKeys(fn ($c) => [$c => env("RATE_{$c}_BDT")])
        ->all(),

    'sell_fee_percent' => env('SHARE_SELL_FEE_PERCENT', 0),
];