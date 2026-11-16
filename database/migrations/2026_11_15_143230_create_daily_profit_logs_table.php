<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_profit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('share_holding_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->constrained()->restrictOnDelete();
            $table->date('profit_date');
            $table->unsignedBigInteger('shares');                 // লাভ পাওয়া শেয়ার
            $table->decimal('share_price_bdt', 18, 2);
            $table->decimal('profit_percent', 6, 3);
            $table->decimal('profit_bdt', 18, 2);
            $table->string('wallet_currency', 3);
            $table->decimal('exchange_rate', 18, 6);
            $table->decimal('wallet_amount', 18, 2);
            $table->timestamps();

            // ডবল লাভ ঠেকানোর মূল গার্ড
            $table->unique(['share_holding_id', 'profit_date']);
            $table->index(['user_id', 'profit_date']);
            $table->index(['project_id', 'profit_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_profit_logs');
    }
};