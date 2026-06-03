<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();

            // Rating detail
            $table->tinyInteger('rating')->comment('1-5');
            $table->tinyInteger('cleanliness_rating')->nullable()->comment('Kebersihan');
            $table->tinyInteger('comfort_rating')->nullable()->comment('Kenyamanan');
            $table->tinyInteger('location_rating')->nullable()->comment('Lokasi');
            $table->tinyInteger('service_rating')->nullable()->comment('Pelayanan');

            $table->text('comment')->nullable();
            $table->string('owner_reply')->nullable();
            $table->timestamp('replied_at')->nullable();

            $table->timestamps();

            $table->unique('booking_id');  // Satu booking hanya boleh 1 review
            $table->index(['property_id', 'rating']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
