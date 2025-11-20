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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();

            // --- Vendor Profile Data ---
            $table->string('owner_name'); 
            $table->text('shop_description');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('national_id')->unique();
            $table->string('phone_number');
            $table->text('location_description'); // Text description of location

            // --- Approval & Status ---
            // Must start as 'pending_approval' as per MVP requirements
            $table->enum('status', ['pending_approval', 'approved', 'rejected', 'suspended'])
                  ->default('pending_approval');
            
            $table->timestamp('approved_at')->nullable(); // Admin sets this on approval

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};