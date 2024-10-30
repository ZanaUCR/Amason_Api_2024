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
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
<<<<<<< HEAD:database/migrations/2024_10_25_222029_create_product_images_table.php
            $table->unsignedBigInteger('product_id'); // Columna para la relación con productos
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
            $table->string('image_path'); // Ruta donde se almacenará la imagen
            $table->timestamps(); // Timestamps created_at y updated_at
=======
            $table->foreignId('product_id')->constrained('products', 'product_id')->onDelete('cascade');  // Relación con 'products' usando 'product_id'
            $table->string('image_path');  // O 'image_path', según sea necesario
            $table->timestamps();
>>>>>>> main:database/migrations/2024_10_30_020032_create_product_images_table.php
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};
