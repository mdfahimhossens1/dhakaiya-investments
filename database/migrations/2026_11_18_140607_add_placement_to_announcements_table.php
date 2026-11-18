<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::table('announcements', function (Blueprint $table) {
        $table->string('placement', 20)->default('notice')->after('type'); // banner | notice | announcement
        $table->string('image_path')->nullable()->after('message');
        $table->string('link_url')->nullable()->after('image_path');
    });
}

public function down(): void
{
    Schema::table('announcements', function (Blueprint $table) {
        $table->dropColumn(['placement', 'image_path', 'link_url']);
    });
}
};
