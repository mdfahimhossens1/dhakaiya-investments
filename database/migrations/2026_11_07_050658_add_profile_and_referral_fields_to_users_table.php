<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // Personal Information
            $table->string('country', 2)
                ->nullable()
                ->after('name');

            $table->string('phone', 20)
                ->nullable()
                ->unique()
                ->after('country');

            // Role Management
            $table->string('role', 30)
                ->default('user')
                ->index()
                ->after('password');

            // Referral System
            $table->string('referral_code', 20)
                ->nullable()
                ->unique()
                ->after('role');

            $table->foreignId('referred_by_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->after('referral_code');
        });

        // Existing users-এর জন্য email optional করা হচ্ছে।
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropForeign(['referred_by_id']);

            $table->dropUnique(['phone']);
            $table->dropUnique(['referral_code']);

            $table->dropIndex(['role']);

            $table->dropColumn([
                'country',
                'phone',
                'role',
                'referral_code',
                'referred_by_id',
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable(false)->change();
        });
    }
};