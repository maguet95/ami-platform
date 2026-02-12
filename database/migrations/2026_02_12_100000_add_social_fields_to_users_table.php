<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('headline', 100)->nullable()->after('twitter_handle');
            $table->string('instagram_handle', 50)->nullable()->after('headline');
            $table->string('youtube_handle', 100)->nullable()->after('instagram_handle');
            $table->string('linkedin_url', 255)->nullable()->after('youtube_handle');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['headline', 'instagram_handle', 'youtube_handle', 'linkedin_url']);
        });
    }
};
