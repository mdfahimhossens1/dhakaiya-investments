<?php

namespace App\Console\Commands;

use App\Services\CurrencyRateSyncService;
use Illuminate\Console\Command;

class SyncCurrencyRates extends Command
{
    protected $signature = 'exchange:sync-rates
                            {--refresh : Refresh existing rates too}';

    protected $description = 'Synchronize supported currency rates against BDT';

    public function handle(CurrencyRateSyncService $service): int
    {
        $this->info('Starting currency rate synchronization...');

        $result = $service->sync(
            (bool) $this->option('refresh')
        );

        $this->newLine();

        $this->info('Updated: ' . count($result['updated']));

        foreach ($result['updated'] as $currency) {
            $this->line("  Updated: {$currency}");
        }

        $this->newLine();

        $this->info('Skipped: ' . count($result['skipped']));

        foreach ($result['skipped'] as $currency) {
            $this->line("  Preserved existing rate: {$currency}");
        }

        $this->newLine();

        $this->warn('Failed / Unsupported: ' . count($result['failed']));

        foreach ($result['failed'] as $currency) {
            $this->line("  Failed: {$currency}");
        }

        $this->newLine();

        $this->info('Currency synchronization completed.');

        return self::SUCCESS;
    }
}