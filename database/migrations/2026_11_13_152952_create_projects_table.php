<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();

            // Company Relationship
            $table->foreignId('company_id')
                ->constrained('companies')
                ->restrictOnDelete();

            // Basic Information
            $table->string('name', 180);
            $table->string('slug', 200)->unique();

            $table->string('image')->nullable();

            // Category
            $table->string('category', 50)->default('other');

            // Description
            $table->longText('description')->nullable();

            // Display-only share quantities
            $table->unsignedBigInteger('total_shares')->default(0);
            $table->unsignedBigInteger('available_shares')->default(0);

            // Display Status
            $table->string('status', 20)->default('upcoming');

            // Product Design Template
            $table->string('template', 50)->default('grid');

            // Display Schedule
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            // Sorting
            $table->unsignedInteger('sort_order')->default(0);

            // Visibility
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index('company_id');
            $table->index('category');
            $table->index('status');
            $table->index('is_active');
            $table->index(['is_featured', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};