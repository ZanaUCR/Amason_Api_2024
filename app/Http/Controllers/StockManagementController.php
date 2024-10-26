<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartProduct;
use App\Models\Product;

class StockController extends Controller
{

    public function updateCartProductQuantity($productId, $quantityChange)
    {
        try {
            $productInCart = $this->findProductInCart($productId);

            if (!$productInCart) {
                return response()->json(['error' => 'Producto no encontrado en el carrito.'], 404);
            }

            $product = $this->findProduct($productId);
            $newQuantity = $productInCart->quantity + $quantityChange;

            if ($quantityChange > 0 && $newQuantity > $product->stock) {
                return response()->json(['error' => 'No hay suficiente stock disponible.'], 400);
            }

            if ($newQuantity <= 0) {
                $productInCart->delete();
                return response()->json(['message' => 'Producto eliminado del carrito.'], 200);
            }
            $productInCart->quantity = $newQuantity;
            $productInCart->save();

            return response()->json(['message' => 'Cantidad actualizada en el carrito con éxito.'], 200);
        } catch (\Exception $e) {

            return response()->json(['error' => 'Ocurrió un error: ' . $e->getMessage()], 500);
        }
    }

    public function increaseProductQuantity(Request $request)
    {
        $productId = $request->input('product_id');
        $quantityIncrease = $request->input('quantity_change');
        return $this->updateCartProductQuantity($productId, $quantityIncrease);
    }

    public function decreaseProductQuantity(Request $request)
    {
        $productId = $request->input('product_id');
        $quantityDecrease = -1 * $request->input('quantity_change');

        return $this->updateCartProductQuantity($productId, $quantityDecrease);
    }

    private function findProductInCart($productId)
    {
        return CartProduct::where('user_id', 1)->where('product_id', $productId)->first();
    }


}
