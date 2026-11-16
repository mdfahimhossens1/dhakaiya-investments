<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::table('projects', function (Blueprint $table) {
        $table->decimal('share_price', 18, 2)->default(0)->after('total_shares');
        $table->decimal('daily_profit_percent', 6, 3)->default(0)->after('share_price');
        $table->unsignedInteger('min_buy_shares')->default(1)->after('daily_profit_percent');
    });
}

public function down(): void
{
    Schema::table('projects', function (Blueprint $table) {
        $table->dropColumn(['share_price', 'daily_profit_percent', 'min_buy_shares']);
    });
}
};
