<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_templates', function (Blueprint $table) {
            $table->id();

            $table->string('name', 120);
            $table->string('slug', 150)->unique();

            $table->string('layout_type', 50);

            $table->string('preview_image')->nullable();

            $table->text('description')->nullable();

            $table->json('settings')->nullable();

            $table->boolean('is_default')->default(false);

            $table->string('status', 20)->default('active');

            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index('layout_type');
            $table->index('status');
            $table->index('is_default');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_templates');
    }
};