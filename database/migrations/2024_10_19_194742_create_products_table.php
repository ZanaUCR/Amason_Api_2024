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
        Schema::create('products', function (Blueprint $table) {
            $table->id("product_id"); // Debería llamarse id
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('id_store');
            $table->foreign('id_store')->references('id')->on('stores')->onDelete('cascade');
            $table->string('name');
            $table->text('description');
            $table->double('price');
            $table->integer('stock');
            $table->integer('discount')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
