<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ranking query: WHERE is_profile_public = true ORDER BY total_xp DESC
        Schema::table('users', function (Blueprint $table) {
            $table->index(['is_profile_public', 'total_xp'], 'users_ranking_index');
        });

        // XP transactions: user_id + created_at for recent activity queries
        Schema::table('xp_transactions', function (Blueprint $table) {
            $table->index(['user_id', 'created_at'], 'xp_transactions_user_recent_index');
        });

        // Lesson progress: quick count of completed lessons per user
        Schema::table('lesson_progress', function (Blueprint $table) {
            $table->index(['user_id', 'is_completed'], 'lesson_progress_user_completed_index');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_ranking_index');
        });

        Schema::table('xp_transactions', function (Blueprint $table) {
            $table->dropIndex('xp_transactions_user_recent_index');
        });

        Schema::table('lesson_progress', function (Blueprint $table) {
            $table->dropIndex('lesson_progress_user_completed_index');
        });
    }
};
