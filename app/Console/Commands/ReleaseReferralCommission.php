<?php

namespace App\Console\Commands;

use App\Models\ReferralCommission;
use App\Services\ReferralCommissionService;
use Illuminate\Console\Command;

class ReleaseReferralCommission extends Command
{
    protected $signature = 'referral:release';
    protected $description = 'Hold period shesh hoa pending referral commission wallet e credit kore';

    public function handle(ReferralCommissionService $service): int
    {
        $count = 0;

        ReferralCommission::where('status', 'pending')
            ->where('release_at', '<=', now())
            ->orderBy('id')
            ->chunkById(200, function ($rows) use ($service, &$count) {
                foreach ($rows as $row) {
                    if ($service->release($row)) $count++;
                }
            });

        $this->info("Released {$count} commission(s).");

        return self::SUCCESS;
    }
}