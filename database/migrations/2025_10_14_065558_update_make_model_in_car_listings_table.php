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
        Schema::table('car_listings', function (Blueprint $table) {
            $table->unsignedBigInteger('make_id')->nullable()->after('user_id');
            $table->unsignedBigInteger('model_id')->nullable()->after('make_id');

            $table->foreign('make_id')->references('id')->on('car_makes')->onDelete('set null');
            $table->foreign('model_id')->references('id')->on('car_models')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('car_listings', function (Blueprint $table) {
             
        });
    }
};
