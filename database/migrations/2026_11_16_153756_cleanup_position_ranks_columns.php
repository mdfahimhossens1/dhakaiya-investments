<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('position_ranks', function (Blueprint $table) {
            foreach (['min_lock_bdt', 'reward_bdt'] as $col) {
                if (Schema::hasColumn('position_ranks', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }

    public function down(): void
    {
    }
};