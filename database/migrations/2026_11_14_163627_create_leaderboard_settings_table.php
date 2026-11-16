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
        Schema::create('leaderboard_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('resell_leaderboard_enabled')->default(true);
            $table->boolean('monthly_leaderboard_enabled')->default(true);
            $table->boolean('top_performers_enabled')->default(true);
            $table->enum('ranking_basis', ['resell_profit', 'resell_volume', 'referral_count', 'investment_amount'])->default('resell_profit');
            $table->enum('reset_cycle', ['weekly', 'monthly', 'never'])->default('monthly');
            $table->unsignedInteger('show_top_n')->default(10);
            $table->boolean('show_amount_publicly')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaderboard_settings');
    }
};
