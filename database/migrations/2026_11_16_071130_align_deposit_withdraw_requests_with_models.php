<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /* DepositRequest model / DepositRequestController যে কলামগুলো ব্যবহার করে */
        Schema::table('deposit_requests', function (Blueprint $table) {
            if (! Schema::hasColumn('deposit_requests', 'method')) {
                $table->string('method', 60)->nullable();
            }
            if (! Schema::hasColumn('deposit_requests', 'sender_number')) {
                $table->string('sender_number', 50)->nullable();
            }
            if (! Schema::hasColumn('deposit_requests', 'transaction_id')) {
                $table->string('transaction_id', 100)->nullable()->index();
            }
            if (! Schema::hasColumn('deposit_requests', 'screenshot')) {
                $table->string('screenshot', 255)->nullable();
            }
            if (! Schema::hasColumn('deposit_requests', 'admin_note')) {
                $table->text('admin_note')->nullable();
            }
        });

        /* WithdrawRequest model / WithdrawRequestController যে কলামগুলো ব্যবহার করে */
        Schema::table('withdraw_requests', function (Blueprint $table) {
            if (! Schema::hasColumn('withdraw_requests', 'method')) {
                $table->string('method', 60)->nullable();
            }
            if (! Schema::hasColumn('withdraw_requests', 'account_name')) {
                $table->string('account_name', 150)->nullable();
            }
            if (! Schema::hasColumn('withdraw_requests', 'account_number')) {
                $table->string('account_number', 100)->nullable();
            }
            if (! Schema::hasColumn('withdraw_requests', 'bank_name')) {
                $table->string('bank_name', 150)->nullable();
            }
            if (! Schema::hasColumn('withdraw_requests', 'branch_name')) {
                $table->string('branch_name', 150)->nullable();
            }
            if (! Schema::hasColumn('withdraw_requests', 'payout_reference')) {
                $table->string('payout_reference', 100)->nullable();
            }
            if (! Schema::hasColumn('withdraw_requests', 'admin_note')) {
                $table->text('admin_note')->nullable();
            }
        });
    }

    public function down(): void
    {
        $drop = [
            'deposit_requests' => ['method', 'sender_number', 'transaction_id', 'screenshot', 'admin_note'],
            'withdraw_requests' => ['method', 'account_name', 'account_number', 'bank_name', 'branch_name', 'payout_reference', 'admin_note'],
        ];

        foreach ($drop as $tableName => $cols) {
            foreach ($cols as $col) {
                if (Schema::hasColumn($tableName, $col)) {
                    Schema::table($tableName, fn (Blueprint $t) => $t->dropColumn($col));
                }
            }
        }
    }
};