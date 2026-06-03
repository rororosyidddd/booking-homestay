<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code', 20)->unique()->comment('Contoh: BKG-20240101-XXXX');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();

            // Detail tamu
            $table->string('guest_name');
            $table->string('guest_email');
            $table->string('guest_phone', 20);
            $table->integer('guest_count')->default(1);

            // Tanggal
            $table->date('check_in');
            $table->date('check_out');
            $table->integer('total_nights');

            // Harga
            $table->decimal('room_price', 12, 2)->comment('Harga per malam saat booking');
            $table->decimal('total_price', 12, 2);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('final_price', 12, 2);

            // Info tambahan
            $table->text('special_request')->nullable();
            $table->enum('status', [
                'pending',      // Menunggu pembayaran
                'confirmed',    // Sudah bayar, dikonfirmasi
                'checked_in',   // Sudah check-in
                'checked_out',  // Sudah check-out
                'cancelled',    // Dibatalkan
                'refunded',     // Dikembalikan
            ])->default('pending');

            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancel_reason')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['room_id', 'check_in', 'check_out']);
            $table->index('booking_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
