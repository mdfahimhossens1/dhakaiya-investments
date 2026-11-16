<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('share_holdings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->constrained()->restrictOnDelete();
            $table->unsignedBigInteger('shares')->default(0);
            $table->unsignedBigInteger('locked_shares')->default(0);
            $table->decimal('invested_bdt', 18, 2)->default(0);   // বর্তমান হোল্ডিংয়ের মোট খরচ (BDT)
            $table->string('status', 20)->default('active');       // active / completed
            $table->timestamps();

            $table->unique(['user_id', 'project_id']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('share_holdings');
    }
};