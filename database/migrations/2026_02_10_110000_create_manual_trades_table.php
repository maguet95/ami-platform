<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manual_trades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('trade_pair_id')->nullable()->constrained()->nullOnDelete();

            // General
            $table->string('direction'); // long, short
            $table->date('trade_date');
            $table->string('timeframe')->nullable(); // 1m, 5m, 15m, 1h, 4h, 1d, 1w
            $table->string('session')->nullable(); // asian, london, new_york, overlap

            // Execution
            $table->decimal('entry_price', 20, 8);
            $table->decimal('exit_price', 20, 8)->nullable();
            $table->decimal('stop_loss', 20, 8)->nullable();
            $table->decimal('take_profit', 20, 8)->nullable();
            $table->decimal('position_size', 20, 8)->nullable();
            $table->decimal('risk_reward_planned', 8, 2)->nullable();
            $table->decimal('risk_reward_actual', 8, 2)->nullable();
            $table->decimal('pnl', 20, 8)->nullable();
            $table->decimal('pnl_percentage', 10, 4)->nullable();
            $table->decimal('commission', 20, 8)->nullable();
            $table->string('status')->default('open'); // open, closed

            // Plan & Discipline
            $table->boolean('had_plan')->default(false);
            $table->unsignedTinyInteger('plan_followed')->nullable(); // 1-5
            $table->text('entry_reason')->nullable();
            $table->text('invalidation_criteria')->nullable();
            $table->jsonb('mistakes')->nullable();
            $table->text('lessons_learned')->nullable();

            // Psychology
            $table->string('emotion_before')->nullable();
            $table->string('emotion_during')->nullable();
            $table->string('emotion_after')->nullable();
            $table->unsignedTinyInteger('confidence_level')->nullable(); // 1-5
            $table->unsignedTinyInteger('stress_level')->nullable(); // 1-5
            $table->text('psychology_notes')->nullable();

            // Market Context
            $table->string('market_condition')->nullable(); // trending_up, trending_down, ranging, volatile, low_volume
            $table->text('key_levels')->nullable();
            $table->text('relevant_news')->nullable();
            $table->text('additional_confluence')->nullable();

            // Reflection
            $table->text('what_i_did_well')->nullable();
            $table->text('what_to_improve')->nullable();
            $table->boolean('would_take_again')->nullable();
            $table->unsignedTinyInteger('overall_rating')->nullable(); // 1-5
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'trade_date']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manual_trades');
    }
};
