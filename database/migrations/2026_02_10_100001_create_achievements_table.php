<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('description');
            $table->string('icon')->default('heroicon-o-trophy');
            $table->string('category'); // learning, engagement, milestone
            $table->integer('xp_reward')->default(0);
            $table->string('requirement_type'); // lessons_completed, courses_completed, login_streak, total_xp, etc.
            $table->integer('requirement_value')->default(1);
            $table->string('tier')->default('bronze'); // bronze, silver, gold, diamond
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};
