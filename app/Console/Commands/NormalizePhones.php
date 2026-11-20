<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Support\Phone;
use Illuminate\Console\Command;

class NormalizePhones extends Command
{
    protected $signature = 'phones:normalize {--dry : শুধু দেখাবে}';

    protected $description = 'সব ইউজারের ফোন E.164 (+880...) ফরম্যাটে আনে এবং দেশের কোড ২ অক্ষরে ঠিক করে';

    public function handle(): int
    {
        $changed = 0;
        $skipped = 0;

        User::orderBy('id')->each(function (User $u) use (&$changed, &$skipped) {
            $iso = Phone::iso2($u->country) ?: 'BD';
            $new = Phone::normalize($u->phone, $iso);

            if ($new === '' || ($new === $u->phone && $iso === $u->country)) {
                return;
            }

            if ($new !== $u->phone && User::where('phone', $new)->where('id', '!=', $u->id)->exists()) {
                $this->warn("User #{$u->id}: {$u->phone} -> {$new} আগেই অন্য ইউজারের, বাদ গেল।");
                $skipped++;

                return;
            }

            $this->line("User #{$u->id} ({$u->role}): {$u->phone} -> {$new}, country {$u->country} -> {$iso}");

            if (! $this->option('dry')) {
                $u->forceFill(['phone' => $new, 'country' => $iso])->save();

                if ($u->agent) {
                    $u->agent->update(['phone' => $new]);
                }
            }

            $changed++;
        });

        $this->info("Changed: {$changed}, Skipped: {$skipped}");

        return self::SUCCESS;
    }
}