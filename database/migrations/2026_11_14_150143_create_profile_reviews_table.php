<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profile_reviews', function (Blueprint $table) {
            $table->id();

            // User being reviewed
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Admin who reviewed the profile
            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Review status
            $table->string('status', 30)->default('pending');

            // Admin review notes
            $table->text('review_notes')->nullable();

            // Review timestamp
            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();

            $table->index('status');
            $table->index('reviewed_at');
            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profile_reviews');
    }
};