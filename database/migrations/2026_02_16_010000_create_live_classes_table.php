<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('live_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->nullable()->constrained('courses')->nullOnDelete();
            $table->foreignId('instructor_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('platform'); // zoom, google_meet, microsoft_teams, discord, other
            $table->string('meeting_url');
            $table->string('meeting_password')->nullable();
            $table->dateTime('starts_at');
            $table->integer('duration_minutes')->default(60);
            $table->string('status')->default('scheduled'); // scheduled, in_progress, completed, cancelled
            $table->boolean('notification_sent')->default(false);
            $table->integer('max_attendees')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('starts_at');
            $table->index('status');
            $table->index(['status', 'starts_at', 'notification_sent']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('live_classes');
    }
};
