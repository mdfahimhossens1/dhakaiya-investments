<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('agents', function (Blueprint $t) {
            $t->string('recharge_currency', 3)->nullable();        
            $t->decimal('discount_percent', 5, 2)->default(0);    
        });

        Schema::table('agent_deposits', function (Blueprint $t) {
            $t->string('currency', 3)->nullable();
            $t->decimal('discount_percent', 5, 2)->default(0);     
            $t->decimal('payable_amount', 14, 2)->nullable();    
        });
    }

    public function down(): void
    {
        Schema::table('agent_deposits', fn (Blueprint $t) => $t->dropColumn(['currency', 'discount_percent', 'payable_amount']));
        Schema::table('agents', fn (Blueprint $t) => $t->dropColumn(['recharge_currency', 'discount_percent']));
    }
};