<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class StockManagementController extends Controller
{

    // Método para incrementar el stock de un producto en el inventario
    private $productModel;

    public function __construct()
    {
       
    }
    

    public function increaseProductStock(Request $request)
    {
        $productId = $request->input('product_id');
        $quantityIncrease = $request->input('quantity_change');
        
        try {
            $product = $this->findProduct($productId);

            // Aumentamos el stock en el inventario
            $product->stock += $quantityIncrease;
            $product->save();

            return response()->json(['message' => 'Stock incrementado con éxito.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ocurrió un error: ' . $e->getMessage()], 500);
        }
    }

    // Método para disminuir el stock de un producto en el inventario
    public function decreaseProductStock(Request $request)
    {
        $productId = $request->input('product_id');
        $quantityDecrease = $request->input('quantity_change');

        try {
            $product = $this->findProduct($productId);

            // Verificamos que el stock disponible sea suficiente
            if ($product->stock < $quantityDecrease) {
                return response()->json(['error' => 'No hay suficiente stock disponible.'], 400);
            }

            // Reducimos el stock en el inventario
            $product->stock -= $quantityDecrease;
            $product->save();

            return response()->json(['message' => 'Stock disminuido con éxito.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ocurrió un error: ' . $e->getMessage()], 500);
        }
    }

    // Método auxiliar para encontrar un producto en el inventario por su ID.
    private function findProduct($productId)
    {
        return product::where('product_id', $productId)->firstOrFail();
    }
}