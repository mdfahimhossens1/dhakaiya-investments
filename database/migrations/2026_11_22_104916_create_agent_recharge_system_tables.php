<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // এজেন্টের Recharge Balance (USD), ইউজারের Investment Wallet থেকে সম্পূর্ণ আলাদা
        Schema::table('agents', function (Blueprint $t) {
            $t->decimal('recharge_balance', 14, 2)->default(0);
        });

        // কোম্পানির কাছে ডিপোজিট রিকোয়েস্ট
        Schema::create('agent_deposits', function (Blueprint $t) {
            $t->id();
            $t->foreignId('agent_id')->constrained()->cascadeOnDelete();
            $t->decimal('amount', 14, 2);
            $t->decimal('approved_amount', 14, 2)->nullable();
            $t->string('method', 50);
            $t->string('transaction_id', 100)->index();
            $t->string('sender_info', 150)->nullable();
            $t->string('status', 20)->default('pending')->index();
            $t->text('admin_note')->nullable();
            $t->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamp('reviewed_at')->nullable();
            $t->timestamps();
        });

        // ইউজার রিচার্জ
        Schema::create('agent_recharges', function (Blueprint $t) {
            $t->id();
            $t->foreignId('agent_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->decimal('amount', 14, 2);                 // USD, এজেন্টের ব্যালেন্স থেকে কাটা
            $t->string('wallet_currency', 3);
            $t->decimal('exchange_rate', 18, 6)->nullable();
            $t->decimal('wallet_amount', 14, 2);          // ইউজারের Wallet-এ যা যোগ হয়েছে
            $t->string('status', 20)->default('completed')->index();
            $t->string('request_token', 64)->unique();    // একই ফর্ম দুবার submit আটকায়
            $t->timestamps();
        });

        // এজেন্টের সম্পূর্ণ লেনদেন (ledger)
        Schema::create('agent_transactions', function (Blueprint $t) {
            $t->id();
            $t->foreignId('agent_id')->constrained()->cascadeOnDelete();
            $t->string('direction', 6);                   // credit | debit
            $t->string('type', 30)->index();              // deposit | recharge | commission | adjustment
            $t->decimal('amount', 14, 2);
            $t->decimal('balance_after', 14, 2);
            $t->nullableMorphs('source');
            $t->string('description')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agent_transactions');
        Schema::dropIfExists('agent_recharges');
        Schema::dropIfExists('agent_deposits');
        Schema::table('agents', fn (Blueprint $t) => $t->dropColumn('recharge_balance'));
    }
};