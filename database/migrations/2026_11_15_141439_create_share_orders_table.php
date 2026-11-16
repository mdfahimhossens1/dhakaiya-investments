<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('share_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->constrained()->restrictOnDelete();
            $table->string('type', 10);                          // buy / sell
            $table->unsignedBigInteger('shares');
            $table->decimal('unit_price_bdt', 18, 2);
            $table->decimal('total_bdt', 18, 2);                 // gross
            $table->decimal('fee_bdt', 18, 2)->default(0);
            $table->decimal('net_bdt', 18, 2);                   // buy: total, sell: total - fee
            $table->string('wallet_currency', 3);
            $table->decimal('exchange_rate', 18, 6);
            $table->decimal('wallet_amount', 18, 2);             // Wallet থেকে কাটা/যোগ হওয়া অঙ্ক
            $table->decimal('profit_loss_bdt', 18, 2)->nullable(); // শুধু sell-এ
            $table->string('status', 20)->default('completed');
            $table->timestamps();

            $table->index(['user_id', 'type']);
            $table->index(['project_id', 'type']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('share_orders');
    }
};