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

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        // 1. Crear las categorías
        $categories = Category::factory(5)->create(); // 5 categorías

        // 2. Crear productos, asignando una categoría a cada producto
        $products = Product::factory(20)->create([
            'category_id' => $categories->random()->id, // Asignar categoría aleatoria
        ]);

        // 3. Crear métodos de pago
        $paymentMethods = PaymentMethod::factory(3)->create(); // 3 métodos de pago

        // 4. Crear usuarios
        $users = User::factory(10)->create(); // 10 usuarios

        // 5. Crear órdenes, asignando un usuario y un método de pago a cada orden
        $orders = $users->map(function ($user) use ($paymentMethods) {
            return Order::factory()->create([
                'user_id' => $user->id,
                'payment_method_id' => $paymentMethods->random()->id,
            ]);
        });

        // 6. Crear order items, asignando productos y órdenes aleatorias
        foreach ($orders as $order) {
            $randomProducts = $products->random(rand(1, 5)); // Asignar entre 1 y 5 productos por orden
            foreach ($randomProducts as $product) {
                OrderItem::factory()->create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => rand(1, 3), // Cantidad entre 1 y 3
                    'price_at_purchase' => $product->price, // Usar el precio del producto al momento de la compra
                ]);
            }
        }
    }
}
