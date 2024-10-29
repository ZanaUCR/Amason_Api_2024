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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id(); // Auto-incremental
            // Foreign key a la tabla 'products'
            $table->unsignedBigInteger('product_id'); // Columna para la relación con métodos de pago
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');

            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('order_id')->on('orders')->onDelete('cascade'); // Relación con la tabla de órdenes
            $table->integer('quantity'); // Cantidad
            $table->double('price_at_purchase'); // Precio al momento de la compra
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
