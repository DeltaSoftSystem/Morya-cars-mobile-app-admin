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
        Schema::create('auction_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('auction_id')->unique();
            $table->enum('status', ['ended', 'cancelled'])->default('ended');
            $table->decimal('base_price', 12, 2);
            $table->decimal('bid_increment', 12, 2);
            $table->decimal('current_bid', 12, 2)->nullable();
            $table->unsignedBigInteger('winner_user_id')->nullable();
            $table->string('winner_name')->nullable();
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->dateTime('started_at')->nullable();
            $table->dateTime('last_bid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auction_results');
    }
};
