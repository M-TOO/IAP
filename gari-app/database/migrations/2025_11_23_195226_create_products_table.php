<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->enum('car_brand', [
                'Ford', 'Toyota', 'Nissan', 'Mazda', 
                'Honda', 'Mercedes', 'BMW', 'Audi'
            ]);
            $table->string('image')->nullable();
            $table->boolean('is_available')->default(true);
            $table->integer('stock_quantity')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};