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
        Schema::create('car_inspections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_listing_id')
                ->constrained('car_listings')
                ->onDelete('cascade');

            $table->string('report_url')->nullable(); // uploaded report file
            $table->date('inspection_date')->nullable();
            $table->string('inspector_name')->nullable();
            $table->string('inspection_center')->nullable();
            $table->json('summary')->nullable(); // JSON of inspection items & status
            $table->enum('status', ['passed', 'failed', 'pending'])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_inspections');
    }
};
