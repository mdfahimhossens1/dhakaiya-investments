<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /* ---------------- deposit_requests ---------------- */
        Schema::table('deposit_requests', function (Blueprint $table) {
            // Error: Unknown column 'approved_amount' / 'reviewed_at'
            if (!Schema::hasColumn('deposit_requests', 'approved_amount')) {
                $table->decimal('approved_amount', 15, 2)->nullable();
            }
            if (!Schema::hasColumn('deposit_requests', 'reviewed_at')) {
                $table->timestamp('reviewed_at')->nullable();
            }
            if (!Schema::hasColumn('deposit_requests', 'reviewed_by')) {
                $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('deposit_requests', 'reject_reason')) {
                $table->text('reject_reason')->nullable();
            }
        });

        // আগের approved রেকর্ডগুলোর জন্য মান ভরে দেওয়া
        DB::statement("UPDATE deposit_requests SET approved_amount = amount - fee WHERE status = 'approved' AND approved_amount IS NULL");
        DB::statement("UPDATE deposit_requests SET reviewed_at = approved_at WHERE reviewed_at IS NULL AND approved_at IS NOT NULL");


        /* ---------------- withdraw_requests ---------------- */
        Schema::table('withdraw_requests', function (Blueprint $table) {
            // Error: Unknown column 'net_amount'
            if (!Schema::hasColumn('withdraw_requests', 'net_amount')) {
                $table->decimal('net_amount', 15, 2)->default(0);
            }
            if (!Schema::hasColumn('withdraw_requests', 'reviewed_at')) {
                $table->timestamp('reviewed_at')->nullable();
            }
            if (!Schema::hasColumn('withdraw_requests', 'reviewed_by')) {
                $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('withdraw_requests', 'reject_reason')) {
                $table->text('reject_reason')->nullable();
            }
        });

        DB::statement("UPDATE withdraw_requests SET net_amount = amount - fee WHERE net_amount = 0");
        DB::statement("UPDATE withdraw_requests SET reviewed_at = approved_at WHERE reviewed_at IS NULL AND approved_at IS NOT NULL");


        /* ---------------- payment_accounts ---------------- */
        // Error: Unknown column 'account_number'
        if (Schema::hasTable('payment_accounts') && !Schema::hasColumn('payment_accounts', 'account_number')) {

            // আগে অন্য নামে নম্বরের কলাম থাকলে সেটাই account_number করে দেওয়া (ডাটা হারাবে না)
            $old = collect(['mobile_number', 'wallet_number', 'account_no', 'number', 'mobile'])
                ->first(fn ($c) => Schema::hasColumn('payment_accounts', $c));

            if ($old) {
                DB::statement("ALTER TABLE payment_accounts CHANGE `{$old}` `account_number` VARCHAR(100) NULL");
            } else {
                Schema::table('payment_accounts', function (Blueprint $table) {
                    $table->string('account_number', 100)->nullable();
                });
            }
        }
    }

    public function down(): void
    {
        foreach (['deposit_requests' => ['approved_amount', 'reviewed_at', 'reject_reason'],
                  'withdraw_requests' => ['net_amount', 'reviewed_at', 'reject_reason']] as $tableName => $cols) {
            foreach ($cols as $col) {
                if (Schema::hasColumn($tableName, $col)) {
                    Schema::table($tableName, fn (Blueprint $t) => $t->dropColumn($col));
                }
            }
        }
    }
};