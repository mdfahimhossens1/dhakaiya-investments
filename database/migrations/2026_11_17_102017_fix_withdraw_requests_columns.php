<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('withdraw_requests', function (Blueprint $table) {
            $add = fn (string $col, callable $def) => Schema::hasColumn('withdraw_requests', $col) ?: $def($table);

            $add('method', fn ($t) => $t->string('method', 60)->nullable());
            $add('account_name', fn ($t) => $t->string('account_name')->nullable());
            $add('account_number', fn ($t) => $t->string('account_number', 100)->nullable());
            $add('bank_name', fn ($t) => $t->string('bank_name')->nullable());
            $add('branch_name', fn ($t) => $t->string('branch_name')->nullable());
            $add('details', fn ($t) => $t->json('details')->nullable());
            $add('net_amount', fn ($t) => $t->decimal('net_amount', 18, 2)->default(0));
            $add('payout_reference', fn ($t) => $t->string('payout_reference', 100)->nullable());
            $add('admin_note', fn ($t) => $t->string('admin_note', 500)->nullable());
            $add('reviewed_by', fn ($t) => $t->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete());
            $add('reviewed_at', fn ($t) => $t->timestamp('reviewed_at')->nullable());
        });
    }

    public function down(): void
    {
    }
};