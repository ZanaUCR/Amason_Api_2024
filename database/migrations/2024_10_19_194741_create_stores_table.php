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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();  // Clave primaria (id de la tienda)
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');  // Relación con la tabla users
            $table->integer('location_id')->nullable();  // ID de la ubicación, puede ser opcional
            $table->string('store_name');
            $table->text('description')->nullable();
            $table->string('email');
            $table->string('logo')->nullable();  // Ruta del logo
            $table->timestamps();  // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
