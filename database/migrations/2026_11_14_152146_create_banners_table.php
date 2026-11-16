<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();

            $table->string('image');
            $table->string('button_text')->nullable();
            $table->string('button_url')->nullable();

            $table->string('status', 20)->default('active');
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index('status');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};