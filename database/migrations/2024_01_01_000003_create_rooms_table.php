<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('capacity')->default(2);
            $table->decimal('price_per_night', 12, 2);
            $table->decimal('weekend_price', 12, 2)->nullable()->comment('Harga khusus Sabtu-Minggu');
            $table->integer('total_rooms')->default(1)->comment('Jumlah unit kamar tipe ini');
            $table->string('bed_type')->nullable()->comment('King, Queen, Twin, Single');
            $table->integer('size_sqm')->nullable()->comment('Luas kamar dalam m2');
            $table->enum('status', ['available', 'unavailable'])->default('available');
            $table->timestamps();

            $table->index('property_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
