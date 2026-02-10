<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('journal_api_keys', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('key_hash', 255);
            $table->string('key_prefix', 8);
            $table->jsonb('permissions')->nullable(); // ["write:entries", "write:summaries"]
            $table->jsonb('allowed_ips')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('key_prefix');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journal_api_keys');
    }
};
