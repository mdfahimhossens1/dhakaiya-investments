<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deposit_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // ✅ payment_method ও payment_account FK
            $table->foreignId('payment_method_id')->nullable()
                ->constrained('payment_methods')->nullOnDelete();
            $table->foreignId('payment_account_id')->nullable()
                ->constrained('payment_accounts')->nullOnDelete();

            $table->string('currency', 3)->default('BDT');
            $table->decimal('amount', 15, 2);
            $table->decimal('fee', 15, 2)->default(0);
            $table->string('status', 20)->default('pending');
            $table->string('reference', 100)->nullable();
            $table->text('note')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deposit_requests');
    }
};