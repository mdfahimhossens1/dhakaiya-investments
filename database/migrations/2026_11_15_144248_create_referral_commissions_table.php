<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referral_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();          // কমিশন যে পাবে
            $table->foreignId('source_user_id')->constrained('users')->cascadeOnDelete(); // যার কারণে
            $table->unsignedTinyInteger('level');
            $table->string('type', 20);                 // profit / share_purchase
            $table->string('period_key', 40);           // 2026-10 অথবা order-15
            $table->decimal('base_bdt', 18, 2);
            $table->decimal('percent', 6, 3);
            $table->decimal('commission_bdt', 18, 2);
            $table->string('status', 20)->default('pending'); // pending / paid / cancelled
            $table->timestamp('release_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->string('wallet_currency', 3)->nullable();
            $table->decimal('exchange_rate', 18, 6)->nullable();
            $table->decimal('wallet_amount', 18, 2)->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'source_user_id', 'type', 'period_key', 'level'], 'ref_comm_unique');
            $table->index(['user_id', 'status']);
            $table->index(['status', 'release_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referral_commissions');
    }
};