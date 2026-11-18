<?php

$codes = [
    'USD', 'INR', 'GBP', 'CAD', 'AUD', 'NZD', 'SGD', 'MYR', 'PKR', 'LKR', 'NPR',
    'SAR', 'AED', 'QAR', 'KWD', 'BHD', 'OMR', 'JOD', 'EGP', 'EUR', 'CHF', 'SEK',
    'NOK', 'DKK', 'PLN', 'CZK', 'TRY', 'RUB', 'UAH', 'JPY', 'CNY', 'HKD', 'KRW',
    'THB', 'IDR', 'PHP', 'VND', 'BRL', 'MXN', 'ZAR', 'NGN', 'KES', 'GHS',
];

return [
    // 1 unit = কত BDT। .env-এ RATE_INR_BDT=... এভাবে বসান। খালি থাকলে ওই কারেন্সি বন্ধ।
    'rates' => ['BDT' => 1],

    'sell_fee_percent' => env('SHARE_SELL_FEE_PERCENT', 0),
    'convert_fee_percent' => env('CONVERT_FEE_PERCENT', 0),
    'convert_min' => env('CONVERT_MIN', 1),
    'profit_autorun' => env('PROFIT_AUTORUN', false),
];