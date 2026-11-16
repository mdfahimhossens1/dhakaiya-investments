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
    Schema::create('reward_categories', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('slug')->unique();
        $table->text('description')->nullable();
        $table->enum('reward_type', ['cash', 'bonus_share', 'gift', 'badge'])->default('cash');
        $table->enum('value_type', ['fixed', 'percent'])->default('fixed');
        $table->decimal('reward_value', 15, 2)->default(0);
        $table->decimal('min_investment', 15, 2)->default(0);
        $table->unsignedInteger('sort_order')->default(0);
        $table->boolean('status')->default(true);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reward_categories');
    }
};
