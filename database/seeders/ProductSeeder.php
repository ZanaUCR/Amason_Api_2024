<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Puedes crear algunos almacenes primero si no los tienes
        $store1 = Store::create(['name' => 'Tienda 1']);
        $store2 = Store::create(['name' => 'Tienda 2']);
        
        // Luego crea productos y asigna el id_store
        Product::factory()->count(10)->create([
            'id_store' => $store1->id, // Asigna el id del almacén que deseas
        ]);

        Product::factory()->count(10)->create([
            'id_store' => $store2->id, // Asigna el id del segundo almacén
        ]);
    }
}
