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
        Schema::create('car_listings', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Relation with user (seller / dealer)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Car details
            $table->string('title')->nullable();
            $table->string('make')->nullable();
            $table->string('model')->nullable();
            $table->string('variant')->nullable();
            $table->integer('year')->nullable();
            $table->integer('km_driven')->nullable();
            $table->string('fuel_type')->nullable();
            $table->string('transmission')->nullable();
            $table->string('body_type')->nullable();
            $table->string('color')->nullable();

            // Pricing
            $table->decimal('price', 15, 2)->nullable();
            $table->decimal('expected_price', 15, 2)->nullable();
            $table->boolean('is_negotiable')->default(false);

            // Ownership and registration
            $table->integer('owner_count')->nullable();
            $table->string('registration_state')->nullable();
            $table->string('registration_city')->nullable();
            $table->string('registration_number')->nullable();

            // Status
            $table->enum('status', ['pending', 'approved', 'rejected', 'inactive'])->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->text('admin_rejection_reason')->nullable();

            // Features
            $table->boolean('has_sunroof')->default(false);
            $table->boolean('has_navigation')->default(false);
            $table->boolean('has_parking_sensor')->default(false);
            $table->boolean('has_reverse_camera')->default(false);
            $table->boolean('has_airbags')->default(false);
            $table->boolean('has_abs')->default(false);
            $table->boolean('has_esp')->default(false);

            // Additional
            $table->string('inspection_report_url')->nullable();
            $table->json('inspection_summary')->nullable();

            // Listing meta
            $table->boolean('is_featured')->default(false);
            $table->unsignedBigInteger('views_count')->default(0);
            $table->unsignedBigInteger('leads_count')->default(0);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_listings');
    }
};
