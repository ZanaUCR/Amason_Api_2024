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
        Schema::create('reviews', function (Blueprint $table) {

            $table->id(); // Clave primari
    
            // Foreign key a la tabla 'users'
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
    
            // Foreign key a la tabla 'products'
            $table->unsignedBigInteger('product_id'); // Columna para la relación con métodos de pago
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');

    
            // Otros campos
            $table->integer('calification'); // Calificación (1-5, por ejemplo)
            $table->string('comment');
            $table->timestamp('review_date')->nullable();
    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
