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
        Schema::create('auctions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_listing_id')->constrained('car_listings')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('app_users')->onDelete('cascade'); // admin who scheduled
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->unsignedBigInteger('base_price');
            $table->string('bid_increment')->default('500'); // in rupees, string for future flexibility
            $table->enum('status', ['pending','scheduled','live','closed','sold','cancelled'])->default('pending');
            $table->unsignedBigInteger('winner_id')->nullable();
            $table->unsignedBigInteger('final_price')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auctions');
    }
};
