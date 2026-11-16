<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /* 1) PaymentAccount model / PaymentInformationController যে কলামগুলো লেখে, সেগুলো যোগ করা */
        Schema::table('payment_accounts', function (Blueprint $table) {
            if (! Schema::hasColumn('payment_accounts', 'method')) {
                $table->string('method', 60)->nullable();          // method code (bkash, nagad ...)
            }
            if (! Schema::hasColumn('payment_accounts', 'account_name')) {
                $table->string('account_name', 150)->nullable();
            }
            if (! Schema::hasColumn('payment_accounts', 'account_number')) {
                $table->string('account_number', 100)->nullable();
            }
            if (! Schema::hasColumn('payment_accounts', 'bank_name')) {
                $table->string('bank_name', 150)->nullable();
            }
            if (! Schema::hasColumn('payment_accounts', 'branch_name')) {
                $table->string('branch_name', 150)->nullable();
            }
        });

        /* 2) পুরোনো unique (user + method + currency) একই method-এ একাধিক অ্যাকাউন্ট আটকে দিত।
              এখন account_number সহ unique রাখা হলো, যাতে ডুপ্লিকেট নম্বর ঠেকে কিন্তু আলাদা নম্বর চলে। */
        $indexes = collect(Schema::getIndexes('payment_accounts'))->pluck('name');

        // user_id foreign key এই unique index-টাই ব্যবহার করছিল, তাই আগে আলাদা index দিতে হবে
        if (! $indexes->contains('payment_accounts_user_id_index')) {
            Schema::table('payment_accounts', fn (Blueprint $t) => $t->index('user_id'));
        }

        if ($indexes->contains('user_method_currency_unique')) {
            Schema::table('payment_accounts', fn (Blueprint $t) => $t->dropUnique('user_method_currency_unique'));
        }

        if (! $indexes->contains('user_method_currency_number_unique')) {
            Schema::table('payment_accounts', fn (Blueprint $t) => $t->unique(
                ['user_id', 'payment_method_id', 'currency', 'account_number'],
                'user_method_currency_number_unique'
            ));
        }
    }

    public function down(): void
    {
        $indexes = collect(Schema::getIndexes('payment_accounts'))->pluck('name');

        if ($indexes->contains('user_method_currency_number_unique')) {
            Schema::table('payment_accounts', fn (Blueprint $t) => $t->dropUnique('user_method_currency_number_unique'));
        }

        Schema::table('payment_accounts', function (Blueprint $table) {
            foreach (['method', 'account_name', 'bank_name', 'branch_name'] as $col) {
                if (Schema::hasColumn('payment_accounts', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};