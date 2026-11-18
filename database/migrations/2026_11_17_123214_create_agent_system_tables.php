<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Super Admin ei duita manage kore
        Schema::create('agent_countries', function (Blueprint $t) {
            $t->id();
            $t->string('name', 100)->unique();
            $t->string('code', 3)->nullable();
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });

        Schema::create('agent_districts', function (Blueprint $t) {
            $t->id();
            $t->foreignId('agent_country_id')->constrained('agent_countries')->cascadeOnDelete();
            $t->string('name', 100);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
            $t->unique(['agent_country_id', 'name']);
        });

        Schema::create('agents', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $t->string('phone', 30)->nullable();
            $t->string('whatsapp', 30)->nullable();
            $t->text('bio')->nullable();
            $t->string('approval_status', 20)->default('approved'); // pending / approved / rejected
            $t->boolean('is_verified')->default(false);
            $t->timestamp('verified_at')->nullable();
            $t->boolean('is_active')->default(true);                // super admin control
            $t->boolean('is_available')->default(true);             // agent nijer control
            $t->timestamps();
            $t->index(['approval_status', 'is_verified', 'is_active', 'is_available'], 'agents_listing_idx');
        });

        Schema::create('agent_locations', function (Blueprint $t) {
            $t->id();
            $t->foreignId('agent_id')->constrained()->cascadeOnDelete();
            $t->foreignId('agent_country_id')->constrained('agent_countries')->cascadeOnDelete();
            $t->foreignId('agent_district_id')->constrained('agent_districts')->cascadeOnDelete();
            $t->timestamps();
            $t->unique(['agent_id', 'agent_district_id']);
        });

        // Research fee nebar jonno. Investment deposit er sathe kono somporko nai
        Schema::create('agent_payment_methods', function (Blueprint $t) {
            $t->id();
            $t->foreignId('agent_id')->constrained()->cascadeOnDelete();
            $t->string('method', 20);               // bkash / nagad / rocket / bank / other
            $t->string('account_name', 120);
            $t->string('account_number', 80);
            $t->string('bank_name', 120)->nullable();
            $t->string('note', 255)->nullable();
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });

        Schema::create('research_requests', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->foreignId('agent_id')->constrained()->cascadeOnDelete();
            $t->foreignId('agent_district_id')->nullable()->constrained('agent_districts')->nullOnDelete();
            $t->string('title', 150);
            $t->text('description');
            // pending > quoted > accepted > in_progress > completed | rejected | cancelled
            $t->string('status', 20)->default('pending');

            $t->decimal('quoted_price', 18, 2)->nullable();
            $t->string('quoted_currency', 3)->nullable();
            $t->unsignedSmallInteger('quoted_days')->nullable();
            $t->text('quote_note')->nullable();
            $t->timestamp('quoted_at')->nullable();
            $t->timestamp('accepted_at')->nullable();

            $t->foreignId('payment_method_id')->nullable()->constrained('agent_payment_methods')->nullOnDelete();
            $t->string('payment_details', 255)->nullable();   // snapshot: "bKash · 01XXXXXXXXX"
            $t->string('payment_reference', 100)->nullable(); // user er dewa TrxID
            $t->timestamp('payment_submitted_at')->nullable();
            $t->timestamp('payment_confirmed_at')->nullable();

            $t->timestamp('completed_at')->nullable();
            $t->string('closed_reason', 255)->nullable();
            $t->timestamps();

            $t->index(['agent_id', 'status']);
            $t->index(['user_id', 'status']);
        });

        Schema::create('research_submissions', function (Blueprint $t) {
            $t->id();
            $t->foreignId('research_request_id')->unique()->constrained()->cascadeOnDelete();
            $t->foreignId('agent_id')->constrained()->cascadeOnDelete();
            $t->string('title', 150);
            $t->longText('report');
            $t->string('attachment_path')->nullable();
            $t->string('attachment_name')->nullable();
            $t->timestamp('submitted_at')->nullable();
            $t->timestamps();
        });

        Schema::create('agent_messages', function (Blueprint $t) {
            $t->id();
            $t->foreignId('agent_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $t->foreignId('research_request_id')->nullable()->constrained()->nullOnDelete();
            $t->text('body');
            $t->timestamp('read_at')->nullable();
            $t->timestamps();
            $t->index(['agent_id', 'user_id', 'id']);
            $t->index(['sender_id', 'read_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agent_messages');
        Schema::dropIfExists('research_submissions');
        Schema::dropIfExists('research_requests');
        Schema::dropIfExists('agent_payment_methods');
        Schema::dropIfExists('agent_locations');
        Schema::dropIfExists('agents');
        Schema::dropIfExists('agent_districts');
        Schema::dropIfExists('agent_countries');
    }
};