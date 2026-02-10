<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trade_pairs', function (Blueprint $table) {
            $table->id();
            $table->string('symbol', 20);
            $table->string('market', 20); // crypto, forex, stocks
            $table->string('display_name', 50);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['symbol', 'market']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trade_pairs');
    }
};
