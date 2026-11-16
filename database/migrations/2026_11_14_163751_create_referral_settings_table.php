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
Schema::create('referral_settings', function (Blueprint $table) {
    $table->id();
    $table->boolean('is_enabled')->default(true);
    $table->enum('commission_on', ['share_purchase', 'profit', 'both'])->default('share_purchase');
    $table->decimal('level1_percent', 5, 2)->default(5);
    $table->decimal('level2_percent', 5, 2)->default(0);
    $table->decimal('level3_percent', 5, 2)->default(0);
    $table->decimal('min_investment_to_qualify', 15, 2)->default(0);
    $table->decimal('max_commission_per_referral', 15, 2)->nullable();
    $table->unsignedInteger('hold_days')->default(0);
    $table->decimal('min_commission_withdraw', 15, 2)->default(0);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_settings');
    }
};
