<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // এখন থেকে ওয়ালেটের পুরো ব্যালেন্সই তোলা যায়
        DB::table('wallets')->update(['withdrawable_balance' => DB::raw('balance')]);
    }

    public function down(): void
    {
        // আগের ভাগাভাগি আর ফেরানো যায় না
    }
};