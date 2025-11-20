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
        Schema::create('spare_parts', function (Blueprint $table) {
            $table->id();

            // --- Foreign Key to Vendor ---
            $table->foreignId('vendor_id')
                  ->constrained('vendors') // Enforce foreign key to 'vendors' table
                  ->onDelete('cascade'); // Delete parts if the vendor is deleted

            // --- Part Details & Filtering ---
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('car_brand')->nullable(); // Optional filter field
            
            // --- Image Storage: stores JSON array of paths ---
            // Used to store paths to the multiple images
            $table->json('image_paths');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spare_parts');
    }
};