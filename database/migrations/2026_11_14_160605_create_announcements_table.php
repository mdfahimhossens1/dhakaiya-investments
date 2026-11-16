<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->longText('message');

            $table->string('type', 20)->default('info');
            // info, success, warning, danger

            $table->string('status', 20)->default('draft');
            // draft, published

            $table->boolean('is_dismissible')->default(true);

            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();

            $table->timestamps();

            $table->index('status');
            $table->index('type');
            $table->index('start_at');
            $table->index('end_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};