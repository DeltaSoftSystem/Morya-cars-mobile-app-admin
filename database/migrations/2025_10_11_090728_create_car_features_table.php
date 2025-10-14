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
        Schema::create('car_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_listing_id')
                ->constrained('car_listings')
                ->onDelete('cascade');

            $table->string('feature_name'); // e.g., "Sunroof", "ABS", "Reverse Camera"
            $table->boolean('is_available')->default(true); // feature status

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_features');
    }
};
