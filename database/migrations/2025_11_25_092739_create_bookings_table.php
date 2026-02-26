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
        Schema::create('bookings', function (Blueprint $table) {
             $table->id();

            $table->unsignedBigInteger('user_id'); // buyer
            $table->unsignedBigInteger('car_listing_id');

            $table->decimal('booking_amount', 10, 2)->default(0);
            $table->enum('payment_mode', ['manual'])->default('manual');
            
            // payment status is independent because manual is slow
            $table->enum('payment_status', ['pending', 'verified', 'rejected'])
                ->default('pending');

            $table->enum('booking_status', ['pending_payment', 'confirmed','completed','seller_paid', 'cancelled'])
                ->default('pending_payment');

            $table->text('admin_comment')->nullable();

            $table->timestamps();

            // Foreign Keys
            $table->foreign('user_id')->references('id')->on('app_users')->onDelete('cascade');
            $table->foreign('car_listing_id')->references('id')->on('car_listings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
