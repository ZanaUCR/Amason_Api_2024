<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartProduct;
use App\Models\Product;

class StockController extends Controller
{

    
    // Método principal para actualizar la cantidad de un producto en el carrito.
    // Este método permite tanto incrementar como reducir la cantidad de un producto.  
    public function updateCartProductQuantity($productId, $quantityChange)
    {
        try {
            // Primero, buscamos el producto en el carrito usando su ID
            $productInCart = $this->findProductInCart($productId);

            // Si no encontramos el producto en el carrito, devolvemos un mensaje de error
            if (!$productInCart) {
                return response()->json(['error' => 'Producto no encontrado en el carrito.'], 404);
            }

            // Luego, obtenemos el producto desde el inventario para verificar el stock disponible
            $product = $this->findProduct($productId);
            // Calculamos la nueva cantidad que tendría el producto en el carrito después de modificarla
            $newQuantity = $productInCart->quantity + $quantityChange;

            // Si estamos aumentando la cantidad, verificamos que no exceda el stock disponible
            if ($quantityChange > 0 && $newQuantity > $product->stock) {
                return response()->json(['error' => 'No hay suficiente stock disponible.'], 400);
            }

            // Si la nueva cantidad es 0 o menor, eliminamos el producto del carrito
            if ($newQuantity <= 0) {
                $productInCart->delete();
                return response()->json(['message' => 'Producto eliminado del carrito.'], 200);
            }
            // Actualizamos la cantidad en el carrito y guardamos los cambios
            $productInCart->quantity = $newQuantity;
            $productInCart->save();

            // Si todo sale bien, enviamos un mensaje de éxito
            return response()->json(['message' => 'Cantidad actualizada en el carrito con éxito.'], 200);
        } catch (\Exception $e) {

            // En caso de un error general, devolvemos un mensaje de error
            return response()->json(['error' => 'Ocurrió un error: ' . $e->getMessage()], 500);
        }
    }

    // Método para incrementar la cantidad de un producto en el carrito.
    public function increaseProductQuantity(Request $request)
    {
        $productId = $request->input('product_id');
        $quantityIncrease = $request->input('quantity_change');
        // Llamamos al método principal de actualización de cantidad con la cantidad a incrementar
        return $this->updateCartProductQuantity($productId, $quantityIncrease);
    }

    // Método para disminuir la cantidad de un producto en el carrito.
    public function decreaseProductQuantity(Request $request)
    {
        $productId = $request->input('product_id');
        $quantityDecrease = -1 * $request->input('quantity_change');

        // Llamamos al método principal de actualización de cantidad con la cantidad negativa
        return $this->updateCartProductQuantity($productId, $quantityDecrease);
    }

    // Método auxiliar para encontrar un producto en el carrito por su ID.
    private function findProductInCart($productId)
    {
        // Usamos el ID del usuario autenticado si está disponible
        return CartProduct::where('user_id', 1)->where('product_id', $productId)->first();
    }

    // Método auxiliar para encontrar un producto en el inventario por su ID.
    private function findProduct($productId)
    {
        return Product::findOrFail($productId);
    }
}
