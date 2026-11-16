<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('position_ranks')) {
            Schema::create('position_ranks', function (Blueprint $table) {
                $table->id();
                $table->string('name', 100);
                $table->string('slug', 120)->unique();
                $table->decimal('min_lock_bdt', 18, 2)->default(0);
                $table->decimal('reward_bdt', 18, 2)->default(0);
                $table->string('status', 20)->default('active');
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();
            });

            return;
        }

        Schema::table('position_ranks', function (Blueprint $table) {
            if (! Schema::hasColumn('position_ranks', 'min_lock_bdt')) {
                $table->decimal('min_lock_bdt', 18, 2)->default(0);
            }
            if (! Schema::hasColumn('position_ranks', 'reward_bdt')) {
                $table->decimal('reward_bdt', 18, 2)->default(0);
            }
            if (! Schema::hasColumn('position_ranks', 'status')) {
                $table->string('status', 20)->default('active');
            }
        });
    }

    public function down(): void
    {
    }
};