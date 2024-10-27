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
        Schema::table('products', function (Blueprint $table) {
            // Agregar la columna id_store y establecer la relación con la tabla stores
            $table->foreignId('id_store')->constrained('stores')->onDelete('cascade')->after('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Eliminar la columna id_store
            $table->dropForeign(['id_store']);
            $table->dropColumn('id_store');
        });
    }
};
