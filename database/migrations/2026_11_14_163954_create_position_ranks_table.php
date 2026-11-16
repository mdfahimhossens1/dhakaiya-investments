<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
    Schema::create('position_ranks', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->unsignedInteger('level')->unique();
        $table->decimal('min_total_investment', 15, 2)->default(0);
        $table->unsignedInteger('min_active_referrals')->default(0);
        $table->decimal('min_resell_volume', 15, 2)->default(0);
        $table->decimal('bonus_amount', 15, 2)->default(0);
        $table->decimal('profit_boost_percent', 5, 2)->default(0);
        $table->unsignedInteger('lock_period_days')->default(0);
        $table->string('badge_color', 7)->default('#087f5b');
        $table->boolean('status')->default(true);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('position_ranks');
    }
};
