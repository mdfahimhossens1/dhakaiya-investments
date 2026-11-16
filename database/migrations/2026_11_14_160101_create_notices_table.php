<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notices', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('slug')->unique();

            $table->longText('content');

            $table->string('priority', 20)->default('normal');

            $table->string('status', 20)->default('draft');

            $table->boolean('is_pinned')->default(false);

            $table->date('publish_date')->nullable();

            $table->date('expiry_date')->nullable();

            $table->timestamps();

            $table->index('status');
            $table->index('priority');
            $table->index('is_pinned');
            $table->index('publish_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notices');
    }
};