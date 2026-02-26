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
              Schema::table('car_listings', function (Blueprint $table) {
                $table->enum('auction_status', [
                    'none',        // default
                    'requested',   // user tapped "Auction Car"
                    'approved',    // admin approved
                    'rejected',    // admin rejected
                    'running',     // auction is live
                    'completed'    // auction finished
                ])->default('none')->after('status');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('car_listings', function (Blueprint $table) {
            $table->dropColumn('auction_status');
        });
    }
};
