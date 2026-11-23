<?php

namespace App\Support;

use App\Services\ShareService;

class AgentMoney
{
    /** "৳1,000.00" (চিহ্ন আপনার Currency::symbol() থেকে) */
    public static function format(string|float|int|null $amount, ?string $currency = null): string
    {
        $currency = strtoupper($currency ?: 'BDT');
        $symbol = Currency::symbol($currency);

        // চিহ্ন যদি শুধু অক্ষর হয় (যেমন CHF, Rs), মাঝে ফাঁকা দেওয়া হয়
        $gap = preg_match('/^[A-Za-z.]+$/', $symbol) ? ' ' : '';

        return $symbol . $gap . number_format((float) $amount, 2);
    }

    /**
     * USD Converted View, যেমন "≈ $82.00"।
     * কারেন্সি নিজেই USD হলে বা রেট না থাকলে null।
     */
    public static function usd(string|float|int|null $amount, ?string $currency = null): ?string
    {
        $currency = strtoupper($currency ?: 'BDT');

        if ($currency === 'USD') {
            return null;
        }

        try {
            $converted = app(ShareService::class)->convertAmount((string) $amount, $currency, 'USD');
        } catch (\Throwable) {
            return null;
        }

        return $converted === null ? null : '≈ $' . number_format((float) $converted, 2);
    }
}