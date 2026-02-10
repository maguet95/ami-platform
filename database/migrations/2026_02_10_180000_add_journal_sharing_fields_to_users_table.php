<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('share_manual_journal')->default(false)->after('is_profile_public');
            $table->boolean('share_automatic_journal')->default(false)->after('share_manual_journal');
            $table->string('automatic_journal_account_type', 100)->nullable()->after('share_automatic_journal');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['share_manual_journal', 'share_automatic_journal', 'automatic_journal_account_type']);
        });
    }
};
