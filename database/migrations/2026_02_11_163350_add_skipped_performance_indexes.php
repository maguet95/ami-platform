<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add indexes that were skipped by add_performance_indexes due to table ordering
        if (Schema::hasTable('xp_transactions') && ! $this->indexExists('xp_transactions', 'xp_transactions_user_recent_index')) {
            Schema::table('xp_transactions', function (Blueprint $table) {
                $table->index(['user_id', 'created_at'], 'xp_transactions_user_recent_index');
            });
        }

        if (Schema::hasColumn('users', 'is_profile_public') && ! $this->indexExists('users', 'users_ranking_index')) {
            Schema::table('users', function (Blueprint $table) {
                $table->index(['is_profile_public', 'total_xp'], 'users_ranking_index');
            });
        }
    }

    public function down(): void
    {
        // Down is handled by the original add_performance_indexes migration
    }

    private function indexExists(string $table, string $indexName): bool
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'pgsql') {
            return DB::selectOne("SELECT 1 FROM pg_indexes WHERE tablename = ? AND indexname = ?", [$table, $indexName]) !== null;
        }

        // SQLite
        $indexes = DB::select("PRAGMA index_list({$table})");

        return collect($indexes)->contains('name', $indexName);
    }
};
