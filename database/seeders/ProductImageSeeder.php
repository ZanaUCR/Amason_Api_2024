<?php

namespace Database\Seeders; // Asegúrate de que este sea correcto

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;

class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Obtiene todos los productos
        $products = Product::all();

        foreach ($products as $product) {
            // Crea un número de imágenes por producto (cambia 3 al número deseado)
            ProductImage::factory()->count(3)->create([
                'product_id' => $product->id, // Asocia la imagen con el producto
                'image_path' => 'path/to/image/' . $product->id . '.jpg', // Cambia la lógica según tus necesidades
            ]);
        }
    }
}
