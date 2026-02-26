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
        Schema::table('app_users', function (Blueprint $table) {

            // Extend role enum
            $table->enum('role', ['buyer', 'seller', 'dealer', 'admin'])
                  ->default('buyer')
                  ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('app_users', function (Blueprint $table) {

            $table->enum('role', ['buyer', 'seller'])
                  ->default('buyer')
                  ->change();
        });
    }
};
