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
              $table->string('last_login_ip', 45)
                  ->nullable()
                  ->after('status');

            $table->dateTime('last_login_at')
                  ->nullable()
                  ->after('last_login_ip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('app_users', function (Blueprint $table) {
            $table->dropColumn([
                'last_login_ip',
                'last_login_at'
            ]);
        });
    }
};
