<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('wallets', 'withdrawable_balance')) {
            Schema::table('wallets', function (Blueprint $table) {
                $table->decimal('withdrawable_balance', 18, 2)->default(0)->after('balance');
            });
        }

        // পুরোনো ডেটা: আয় + refund - withdraw, ০ থেকে মোট ব্যালেন্সের মধ্যে
        DB::table('wallets')->orderBy('id')->each(function ($w) {
            $earn = (string) DB::table('wallet_transactions')
                ->where('wallet_id', $w->id)->where('direction', 'credit')
                ->whereIn('type', ['daily_profit', 'referral_commission', 'reward', 'withdraw_refund'])
                ->sum('amount');

            $out = (string) DB::table('wallet_transactions')
                ->where('wallet_id', $w->id)->where('direction', 'debit')
                ->where('type', 'withdraw')->sum('amount');

            $val = bcsub($earn, $out, 2);

            if (bccomp($val, '0', 2) < 0) {
                $val = '0.00';
            }
            if (bccomp($val, (string) $w->balance, 2) > 0) {
                $val = (string) $w->balance;
            }

            DB::table('wallets')->where('id', $w->id)->update(['withdrawable_balance' => $val]);
        });
    }

    public function down(): void
    {
        Schema::table('wallets', fn (Blueprint $t) => $t->dropColumn('withdrawable_balance'));
    }
};