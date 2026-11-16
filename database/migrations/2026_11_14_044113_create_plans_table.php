<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();

            $table->string('name', 100);
            $table->string('slug', 120)->unique();

            $table->text('description')->nullable();

            $table->unsignedSmallInteger('duration_days')->default(30);

            // Display-only price; no payment processing.
            $table->decimal('display_price', 10, 2)->default(0);

            // Plan features stored as JSON.
            $table->json('features')->nullable();

            $table->boolean('is_featured')->default(false);

            $table->string('status', 20)->default('active');

            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index('status');
            $table->index(['is_featured', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};