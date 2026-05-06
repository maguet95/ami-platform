<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crypto_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('access_grant_id')->nullable()->constrained()->nullOnDelete();
            $table->string('now_payment_id')->unique();
            $table->string('order_id')->unique();
            // waiting, confirming, confirmed, sending, finished, partially_paid, failed, expired
            $table->string('status')->default('waiting');
            $table->decimal('price_amount', 10, 2);
            $table->string('price_currency', 10)->default('usd');
            $table->string('pay_currency', 30);
            $table->string('pay_address');
            $table->decimal('pay_amount', 20, 8)->nullable();
            $table->decimal('actually_paid', 20, 8)->nullable();
            $table->string('duration_type');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crypto_payments');
    }
};
