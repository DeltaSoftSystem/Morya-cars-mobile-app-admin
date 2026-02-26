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
             $table->string('insurance_company', 100)
                  ->nullable()
                  ->after('registration_number');

            $table->string('insurance_policy_number', 100)
                  ->nullable()
                  ->after('insurance_company');

            $table->date('insurance_upto')
                  ->nullable()
                  ->after('insurance_policy_number');

            $table->string('pucc_number', 50)
                  ->nullable()
                  ->after('insurance_upto');

            $table->date('pucc_upto')
                  ->nullable()
                  ->after('pucc_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('car_listings', function (Blueprint $table) {
            $table->dropColumn([
                'insurance_company',
                'insurance_policy_number',
                'insurance_upto',
                'pucc_number',
                'pucc_upto',
            ]);
        });
    }
};
