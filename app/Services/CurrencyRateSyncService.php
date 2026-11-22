<?php

namespace App\Services;

use App\Models\CurrencyRate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class CurrencyRateSyncService
{
    public function sync(bool $refresh = false): array
    {
        $codes = config('exchange.currencies', []);

        $updated = [];
        $skipped = [];
        $failed = [];

        foreach ($codes as $currency) {

            $currency = strtoupper($currency);

            if ($currency === 'BDT') {
                continue;
            }

            // Existing admin rate preserve করা
            if (!$refresh && CurrencyRate::where('currency', $currency)->exists()) {
                $skipped[] = $currency;
                continue;
            }

            try {

                $url = rtrim(config('exchange.api.url'), '/')
                    . "/rate/{$currency}/BDT";

                $response = Http::timeout(
                    config('exchange.api.timeout', 15)
                )->retry(2, 500)->get($url);

                if (!$response->successful()) {
                    $failed[] = $currency;
                    continue;
                }

                $data = $response->json();

                $rate = $data['rate'] ?? null;

                if (
                    !is_numeric($rate) ||
                    (float) $rate <= 0
                ) {
                    $failed[] = $currency;
                    continue;
                }

                CurrencyRate::updateOrCreate(
                    ['currency' => $currency],
                    [
                        'rate_to_bdt' => number_format(
                            (float) $rate,
                            6,
                            '.',
                            ''
                        ),
                        'updated_by' => null,
                    ]
                );

                $updated[] = $currency;

            } catch (\Throwable $e) {

                Log::warning('Currency rate sync failed', [
                    'currency' => $currency,
                    'error' => $e->getMessage(),
                ]);

                $failed[] = $currency;
            }
        }

        CurrencyRate::forget();

        return [
            'updated' => $updated,
            'skipped' => $skipped,
            'failed' => $failed,
        ];
    }
}