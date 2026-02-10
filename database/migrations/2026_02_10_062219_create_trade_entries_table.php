<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trade_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('trade_pair_id')->constrained('trade_pairs');
            $table->string('external_id', 100);
            $table->enum('direction', ['long', 'short']);
            $table->decimal('entry_price', 18, 8);
            $table->decimal('exit_price', 18, 8)->nullable();
            $table->decimal('quantity', 18, 8);
            $table->decimal('pnl', 18, 8)->nullable();
            $table->decimal('pnl_percentage', 8, 4)->nullable();
            $table->decimal('fee', 18, 8)->default(0);
            $table->timestamp('opened_at');
            $table->timestamp('closed_at')->nullable();
            $table->integer('duration_seconds')->nullable();
            $table->enum('status', ['open', 'closed', 'cancelled'])->default('open');
            $table->jsonb('tags')->nullable();
            $table->text('notes')->nullable();
            $table->string('source', 50);
            $table->timestamps();

            $table->index(['user_id', 'closed_at'], 'trade_entries_user_closed_index');
            $table->index(['user_id', 'trade_pair_id'], 'trade_entries_user_pair_index');
            $table->unique(['user_id', 'external_id', 'source'], 'trade_entries_dedup_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trade_entries');
    }
};
