<?php

namespace App\Console\Commands;

use App\Services\ReferralService;
use Illuminate\Console\Command;

class ReleaseReferralCommission extends Command
{
    protected $signature = 'referral:release';
    protected $description = 'Hold period শেষ হওয়া pending referral commission wallet-এ credit করে';

    public function handle(ReferralService $referrals): int
    {
        $stats = $referrals->releaseDue();

        $this->table(
            ['Released', 'Users', 'Waiting (below min)'],
            [[$stats['released'], $stats['users'], $stats['waiting']]]
        );

        return self::SUCCESS;
    }
}