<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Store;
use App\Models\ProductImage;
use Database\Factories\CategoryFactory;
use Database\Factories\ProductFactory;
use Database\Factories\PaymentMethodFactory;
use Database\Sedder\ProductImageSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        // 1. Crear las categorías
        $categories = Category::factory(5)->create(); // 5 categorías
    
        // 2. Crear usuarios (vendedores) y sus tiendas asociadas
        $users = User::factory(10)->create(); // 10 usuarios (vendedores)
        $stores = $users->map(function ($user) {
            return Store::factory()->create([
                'seller_id' => $user->id,
            ]);
        });
    
        // 3. Crear productos, asignando una categoría y tienda aleatoria a cada producto
        $products = Product::factory(20)->make()->each(function ($product) use ($categories, $stores) {
            $product->category_id = $categories->random()->id; // Asignar categoría aleatoria
            $product->id_store = $stores->random()->id;        // Asignar tienda aleatoria
            $product->save();
        });
    
        // 4. Crear métodos de pago
        $paymentMethods = PaymentMethod::factory(3)->create(); // 3 métodos de pago
    
        // 5. Crear órdenes, asignando un usuario y un método de pago a cada orden
        $orders = $users->map(function ($user) use ($paymentMethods) {
            return Order::factory()->create([
                'user_id' => $user->id,
                'payment_method_id' => $paymentMethods->random()->id,
            ]);
        });
    
        // 6. Crear order items, asignando productos y órdenes aleatorias
        foreach ($orders as $order) {
            // Asegurarse de que solo se seleccionan productos que no tienen 'product_id' como null
            $randomProducts = $products->random(rand(1, 5)); // Asignar entre 1 y 5 productos por orden
            foreach ($randomProducts as $product) {
                if ($product->id) { // Verificar que el ID del producto no sea null
                    OrderItem::factory()->create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => rand(1, 3), // Cantidad entre 1 y 3
                        'price_at_purchase' => $product->price, // Usar el precio del producto al momento de la compra
                    ]);
                } else {
                    // Manejar el caso en que el ID del producto es null
                    \Log::error('Intento de crear OrderItem con product_id null');
                }
            }
        }

     
    }
    
    
    
    
}
