<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('car_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_listing_id')
                ->constrained('car_listings')
                ->onDelete('cascade');

            $table->string('image_path'); // storage/uploads/cars/...
            $table->boolean('is_primary')->default(false); // main thumbnail
            $table->integer('sort_order')->default(0); // image order

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_images');
    }
};
