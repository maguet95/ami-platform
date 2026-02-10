<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('journal_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('period_type', ['daily', 'weekly', 'monthly', 'all_time']);
            $table->date('period_start');
            $table->date('period_end');
            $table->integer('total_trades')->default(0);
            $table->integer('winning_trades')->default(0);
            $table->integer('losing_trades')->default(0);
            $table->decimal('win_rate', 5, 2)->default(0);
            $table->decimal('total_pnl', 18, 8)->default(0);
            $table->decimal('max_drawdown', 8, 4)->default(0);
            $table->decimal('best_trade_pnl', 18, 8)->default(0);
            $table->decimal('worst_trade_pnl', 18, 8)->default(0);
            $table->integer('avg_trade_duration')->default(0);
            $table->decimal('profit_factor', 8, 4)->default(0);
            $table->jsonb('metadata')->nullable();
            $table->timestamp('calculated_at');
            $table->timestamps();

            $table->unique(['user_id', 'period_type', 'period_start'], 'journal_summaries_period_index');
            $table->index(['user_id', 'period_type'], 'journal_summaries_user_type_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journal_summaries');
    }
};
