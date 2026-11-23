<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // বিক্রির জন্য পোস্ট করা শেয়ার reserve রাখার কলাম
        Schema::table('share_holdings', function (Blueprint $t) {
            $t->unsignedInteger('listed_shares')->default(0)->after('locked_shares');
        });

        // মার্কেটপ্লেসের পোস্ট
        Schema::create('share_listings', function (Blueprint $t) {
            $t->id();
            $t->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
            $t->foreignId('project_id')->constrained()->cascadeOnDelete();
            $t->unsignedInteger('shares');                       // মোট পোস্ট করা
            $t->unsignedInteger('remaining_shares');
            $t->decimal('unit_price_bdt', 16, 4);                // প্রতি শেয়ার, BDT-তে (ভিত্তি মূল্য)
            $t->string('list_currency', 3);                      // বিক্রেতা যে কারেন্সিতে দাম দিয়েছে
            $t->decimal('list_unit_price', 16, 2);               // বিক্রেতার দেওয়া দাম (দেখানোর জন্য)
            $t->string('status', 20)->default('active')->index(); // active | sold | cancelled
            $t->timestamp('closed_at')->nullable();
            $t->timestamps();
            $t->index(['project_id', 'status']);
        });

        // সম্পন্ন লেনদেন
        Schema::create('share_trades', function (Blueprint $t) {
            $t->id();
            $t->foreignId('listing_id')->constrained('share_listings')->cascadeOnDelete();
            $t->foreignId('project_id')->constrained()->cascadeOnDelete();
            $t->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
            $t->foreignId('buyer_id')->constrained('users')->cascadeOnDelete();
            $t->unsignedInteger('shares');
            $t->decimal('unit_price_bdt', 16, 4);
            $t->decimal('total_bdt', 16, 2);
            $t->decimal('fee_percent', 6, 3);                    // কোম্পানির কমিশন %
            $t->decimal('fee_bdt', 16, 2);
            $t->decimal('seller_net_bdt', 16, 2);
            $t->string('buyer_currency', 3);
            $t->decimal('buyer_rate', 18, 8);
            $t->decimal('buyer_amount', 16, 2);                  // ক্রেতার Wallet থেকে যা কাটা হয়েছে
            $t->string('seller_currency', 3);
            $t->decimal('seller_rate', 18, 8);
            $t->decimal('seller_amount', 16, 2);                 // বিক্রেতার Wallet-এ যা যোগ হয়েছে
            $t->decimal('seller_profit_loss_bdt', 16, 2)->default(0);
            $t->timestamps();
            $t->index(['seller_id', 'created_at']);
            $t->index(['buyer_id', 'created_at']);
        });

        // কোন ShareOrder কোন মার্কেটপ্লেস লেনদেন থেকে এসেছে
        Schema::table('share_orders', function (Blueprint $t) {
            $t->foreignId('share_trade_id')->nullable()->constrained('share_trades')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('share_orders', function (Blueprint $t) {
            $t->dropConstrainedForeignId('share_trade_id');
        });
        Schema::dropIfExists('share_trades');
        Schema::dropIfExists('share_listings');
        Schema::table('share_holdings', fn (Blueprint $t) => $t->dropColumn('listed_shares'));
    }
};