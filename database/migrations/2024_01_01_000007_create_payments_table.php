<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->string('payment_code', 30)->unique();
            $table->decimal('amount', 12, 2);

            // Metode pembayaran
            $table->string('method')->nullable()->comment('bank_transfer, credit_card, gopay, dll');
            $table->string('provider')->nullable()->comment('midtrans, xendit, dll');

            // Data dari payment gateway
            $table->string('reference_id')->nullable()->comment('Order ID dari gateway');
            $table->string('snap_token')->nullable()->comment('Token Midtrans Snap');
            $table->json('gateway_response')->nullable()->comment('Raw response dari gateway');

            // Status
            $table->enum('status', [
                'pending',
                'paid',
                'failed',
                'expired',
                'refunded',
            ])->default('pending');

            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();

            $table->index('booking_id');
            $table->index('reference_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
