<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();

            $table->string('question');
            $table->longText('answer');

            $table->string('category')->nullable();

            $table->string('status', 20)->default('draft');

            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index('status');
            $table->index('category');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};