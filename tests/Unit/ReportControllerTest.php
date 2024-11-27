<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Store;

class ReportControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_top_selling_products_by_store_success()
    {
        // Crear un store
        $store = Store::factory()->create();

        // Crear productos asociados al store
        $products = Product::factory()->count(3)->create(['id_store' => $store->id]);

        // Crear OrderItems asociados a los productos
        foreach ($products as $index => $product) {
            OrderItem::factory()->create([
                'product_id' => $product->product_id,
                'quantity' => ($index + 1) * 10, // Cantidad variable para probar el ordenamiento
                'created_at' => now()->subDays(5), // Dentro del rango de fechas
            ]);
        }

        // Hacer la petición con un rango válido de fechas
        $response = $this->json('GET', route('reports.top-selling-products', ['storeId' => $store->id]), [
            'start_date' => now()->subDays(10)->toDateString(),
            'end_date' => now()->toDateString(),
        ]);

        $response->assertStatus(200);
        $response->assertJsonCount(3); // Verificar que hay 3 productos en la respuesta

        // Verificar que los productos estén en orden descendente de ventas
        $response->assertJsonFragment(['total_sold' => '30']);
        $response->assertJsonFragment(['total_sold' => '20']);
        $response->assertJsonFragment(['total_sold' => '10']);
        
    }

    public function test_get_top_selling_products_by_store_no_products()
    {
        // Crear un store sin productos
        $store = Store::factory()->create();

        // Hacer la petición con un rango válido de fechas
        $response = $this->json('GET', route('reports.top-selling-products', ['storeId' => $store->id]), [
            'start_date' => now()->subDays(10)->toDateString(),
            'end_date' => now()->toDateString(),
        ]);

        $response->assertStatus(404);
        $response->assertJson(['message' => 'No se han encontrado productos vendidos en el período seleccionado.']);
    }

    public function test_export_top_selling_products_pdf_success()
    {
        // Crear un store
        $store = Store::factory()->create();

        // Crear productos asociados al store
        $products = Product::factory()->count(2)->create(['id_store' => $store->id]);

        // Crear OrderItems asociados a los productos
        foreach ($products as $product) {
            OrderItem::factory()->create([
                'product_id' => $product->product_id,
                'quantity' => 5,
                'created_at' => now()->subDays(2),
            ]);
        }

        // Hacer la petición para exportar el PDF
        $response = $this->json('GET', route('reports.export-pdf', ['storeId' => $store->id]), [
            'start_date' => now()->subDays(5)->toDateString(),
            'end_date' => now()->toDateString(),
        ]);

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }
}