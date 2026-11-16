<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ===== 1) payment_methods =====
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('code', 60)->unique();
            $table->string('type', 20)->default('mobile');      // mobile / bank / crypto / other
            $table->string('icon', 20)->nullable();
            $table->text('description')->nullable();
            $table->json('countries')->nullable();              // null = all countries
            $table->json('supported_currencies')->nullable();   // null = all currencies
            $table->boolean('deposit_enabled')->default(true);
            $table->boolean('withdraw_enabled')->default(true);
            $table->text('deposit_account')->nullable();
            $table->text('deposit_instructions')->nullable();
            $table->decimal('withdraw_fee_percent', 5, 2)->default(0);
            $table->string('status', 20)->default('active');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['status', 'sort_order']);
        });

        // ===== 2) payment_accounts (নতুন করে তৈরি) =====
        Schema::create('payment_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payment_method_id')
                ->nullable()
                ->constrained('payment_methods')
                ->nullOnDelete();
            $table->string('currency', 3)->nullable();
            $table->json('details')->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->timestamps();

            $table->unique(
                ['user_id', 'payment_method_id', 'currency'],
                'user_method_currency_unique'
            );
        });

        // ===== 3) deposit_requests / withdraw_requests-এ payment_method_id =====
        if (Schema::hasTable('deposit_requests')) {
            Schema::table('deposit_requests', function (Blueprint $table) {
                if (! Schema::hasColumn('deposit_requests', 'payment_method_id')) {
                    $table->foreignId('payment_method_id')->nullable()
                        ->after('user_id')
                        ->constrained('payment_methods')
                        ->nullOnDelete();
                }
            });
        }

        if (Schema::hasTable('withdraw_requests')) {
            Schema::table('withdraw_requests', function (Blueprint $table) {
                if (! Schema::hasColumn('withdraw_requests', 'payment_method_id')) {
                    $table->foreignId('payment_method_id')->nullable()
                        ->after('user_id')
                        ->constrained('payment_methods')
                        ->nullOnDelete();
                }
                if (! Schema::hasColumn('withdraw_requests', 'details')) {
                    $table->json('details')->nullable();
                }
            });
        }

        // ===== 4) ডিফল্ট ৪টা method seed =====
        $now = now();
        $defaults = [
            ['bkash',  'bKash',         'mobile', '📱', ['BD'], ['BDT'], 1],
            ['nagad',  'Nagad',         'mobile', '📱', ['BD'], ['BDT'], 2],
            ['rocket', 'Rocket',        'mobile', '📱', ['BD'], ['BDT'], 3],
            ['bank',   'Bank Transfer', 'bank',   '🏦', null,   null,    4],
        ];

        foreach ($defaults as [$code, $name, $type, $icon, $countries, $currencies, $sort]) {
            DB::table('payment_methods')->insert([
                'name'                 => $name,
                'code'                 => $code,
                'type'                 => $type,
                'icon'                 => $icon,
                'countries'            => $countries ? json_encode($countries) : null,
                'supported_currencies' => $currencies ? json_encode($currencies) : null,
                'deposit_enabled'      => true,
                'withdraw_enabled'     => true,
                'deposit_account'      => config("payment.methods.$code.number"),
                'status'               => 'active',
                'sort_order'           => $sort,
                'created_at'           => $now,
                'updated_at'           => $now,
            ]);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('deposit_requests') && Schema::hasColumn('deposit_requests', 'payment_method_id')) {
            Schema::table('deposit_requests', fn (Blueprint $t) => $t->dropConstrainedForeignId('payment_method_id'));
        }

        if (Schema::hasTable('withdraw_requests')) {
            Schema::table('withdraw_requests', function (Blueprint $t) {
                if (Schema::hasColumn('withdraw_requests', 'payment_method_id')) {
                    $t->dropConstrainedForeignId('payment_method_id');
                }
                if (Schema::hasColumn('withdraw_requests', 'details')) {
                    $t->dropColumn('details');
                }
            });
        }

        Schema::dropIfExists('payment_accounts');
        Schema::dropIfExists('payment_methods');
    }
};