<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('live_class_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('live_class_id')->constrained('live_classes')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('status')->default('registered'); // registered, notified, attended
            $table->dateTime('notified_at')->nullable();
            $table->dateTime('attended_at')->nullable();
            $table->string('access_token', 64)->unique();
            $table->timestamps();

            $table->unique(['live_class_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('live_class_attendances');
    }
};
