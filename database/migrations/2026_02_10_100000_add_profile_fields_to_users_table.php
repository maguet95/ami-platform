<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->nullable()->after('name');
            $table->text('bio')->nullable();
            $table->string('avatar')->nullable();
            $table->string('location', 100)->nullable();
            $table->string('twitter_handle', 50)->nullable();
            $table->date('trading_since')->nullable();
            $table->boolean('is_profile_public')->default(false);
            $table->integer('total_xp')->default(0);
            $table->integer('current_streak')->default(0);
            $table->integer('longest_streak')->default(0);
            $table->date('last_active_date')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username', 'bio', 'avatar', 'location', 'twitter_handle',
                'trading_since', 'is_profile_public', 'total_xp',
                'current_streak', 'longest_streak', 'last_active_date',
            ]);
        });
    }
};
