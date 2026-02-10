<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->text('content')->nullable();
            $table->string('video_url')->nullable();
            $table->string('video_provider')->nullable(); // bunny, youtube, vimeo
            $table->string('type')->default('video'); // video, text, quiz
            $table->integer('duration_minutes')->default(0);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_published')->default(false);
            $table->boolean('is_free_preview')->default(false);
            $table->timestamps();

            $table->unique(['module_id', 'slug']);
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
