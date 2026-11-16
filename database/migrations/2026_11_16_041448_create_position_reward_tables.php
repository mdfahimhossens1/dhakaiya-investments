<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('position_rank_id')->nullable()->constrained('position_ranks')->nullOnDelete();
            $table->decimal('locked_value_bdt', 18, 2)->default(0);
            $table->timestamp('achieved_at')->nullable();
            $table->timestamp('reward_paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('position_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('from_rank_id')->nullable()->constrained('position_ranks')->nullOnDelete();
            $table->foreignId('to_rank_id')->nullable()->constrained('position_ranks')->nullOnDelete();
            $table->string('action', 20);              // promoted / demoted
            $table->decimal('locked_value_bdt', 18, 2)->default(0);
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });

        Schema::create('share_locks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('share_holding_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->constrained()->restrictOnDelete();
            $table->unsignedBigInteger('shares');
            $table->string('status', 20)->default('locked');   // locked / released
            $table->timestamp('locked_at');
            $table->timestamp('released_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });

        Schema::create('reward_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('category', 30);            // position / resell / referral
            $table->string('period_key', 60);
            $table->string('title');
            $table->decimal('amount_bdt', 18, 2);
            $table->string('wallet_currency', 3);
            $table->decimal('exchange_rate', 18, 6);
            $table->decimal('wallet_amount', 18, 2);
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'category', 'period_key']);   // ডবল reward ঠেকায়
            $table->index(['category', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reward_logs');
        Schema::dropIfExists('share_locks');
        Schema::dropIfExists('position_histories');
        Schema::dropIfExists('user_positions');
    }
};