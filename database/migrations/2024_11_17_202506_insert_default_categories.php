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
        // Insertar las categorías
        DB::table('categories')->insert([
            ['id' => 1, 'name' => 'Electronics', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Clothing', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Books', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Toys & Games', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'name' => 'Automotive', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'name' => 'Beauty & Personal Care', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminar las categorías insertadas
        DB::table('categories')->whereIn('id', [1, 2, 3, 4, 5, 6])->delete();
    }
};
