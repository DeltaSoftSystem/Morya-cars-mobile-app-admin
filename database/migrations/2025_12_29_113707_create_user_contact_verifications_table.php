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
        Schema::create('user_contact_verifications', function (Blueprint $table) {
              $table->id();

            $table->unsignedBigInteger('user_id');

            // email | mobile
            $table->enum('type', ['email', 'mobile']);

            // new email or new mobile number
            $table->string('value');

            // OTP (6 digit recommended)
            $table->string('otp', 10);

            $table->timestamp('expires_at')->nullable();
            $table->timestamp('verified_at')->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'type']);
            $table->index('otp');

            // Foreign key
            $table->foreign('user_id')
                  ->references('id')
                  ->on('app_users')
                  ->onDelete('cascade');
       
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_contact_verifications');
    }
};
