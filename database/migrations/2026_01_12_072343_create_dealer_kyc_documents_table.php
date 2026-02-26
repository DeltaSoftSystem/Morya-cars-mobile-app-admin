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
        Schema::create('dealer_kyc_documents', function (Blueprint $table) {
             $table->id();
            $table->unsignedBigInteger('user_id');

            $table->enum('document_type', [
                'business_proof',
                'gst',
                'id_proof'
            ]);

            $table->string('document_path');

            $table->enum('status', ['pending', 'approved', 'rejected'])
                  ->default('pending');

            $table->text('admin_remark')->nullable();

            $table->timestamps();

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
        Schema::dropIfExists('dealer_kyc_documents');
    }
};
