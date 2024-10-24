<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\cart_products;
use App\Models\product;

class CartProductsController extends Controller
{

    public function index($userId)
    {
        $listcartproducts = cart_products::where('user_id', $userId)->get();
        $totalamount = 0;

        // Agregando productos con detalles como nombre y precio
        foreach ($listcartproducts as $cartproduct) {
            $product = Product::find($cartproduct->product_id);
            $cartproduct->product_name = $product->name;
            $cartproduct->product_price = $product->price;
            $totalamount += $product->price * $cartproduct->quantity;
        }


        return response()->json([
            'cart_products' => $listcartproducts,
            'total_amount' => $totalamount
        ]);
    }


    //todo    Metodo para agregar al carrito, se recibe un objeto cart_products, con quantity y product_id definidos, el user_id se obtiene de la session
    public function addtocart(Request $request)
    {
        $idproducttoadd = $request->input('idproducttoadd');
        $quantitytoadd = $request->input('quantitytoadd');
        try {
            //*validar que exista el producto en el carrito
            //$productincart = cart_products::where('user_id', auth()->user()->id)->where('product_id', $idproducttoadd)->first();
            $productincart = cart_products::where('user_id', 1)->where('product_id', $idproducttoadd)->first();



            //*validar que haya stock del producto
            $product = Product::findOrFail($idproducttoadd);
            $quantity = $quantitytoadd;

            //* se verifica si hay mas stock del que se agrega
            if ($product->stock >= $quantity) {

                //*se verifica si ya existe ese producto en el carrito
                if ($productincart) {
                    //* se actualiza la cantidad
                    $productincart->quantity += $quantity;
                    if ($product->stock < $productincart->quantity) {
                        return response()->json(['error' => 'No hay suficiente stock disponible después de la actualización.'], 400);
                    }
                    $productincart->save();
                } else {

                    //* se agrega el producto
                    $newproductincart = new cart_products();
                    //? $newproductincart->user_id = auth()->user()->id;
                    $newproductincart->user_id = 1;
                    $newproductincart->product_id = $idproducttoadd;
                    $newproductincart->quantity = $quantitytoadd;
                    $newproductincart->save();
                }
                //* mensaje caso exitoso
                return response()->json(['message' => 'Cantidad actualizada en el carrito.'], 200);
            } else {

                //! mensaje caso no hay stock
                return response()->json(['error' => 'No hay suficiente stock disponible.'], 400);
            }
            //* mensaje caso exitoso
            return response()->json(['message' => 'El producto se ha agregado al carrito.'], 201);
        } catch (\Exception $e) {
            //! mensaje de error
            return response()->json(['error' => 'Ocurrió un error al agregar el producto al carrito: ' . $e->getMessage()], 500);
        }
    }

    //todo    Metodo para remover un producto del carrito 
    public function removeProductUnits(Request $request)
    {
        try {
            $idproducttoremove = $request->input('idproducttoremove');
            $quantitytoremove = $request->input('quantitytoremove');
            //* Buscar el producto en el carrito 
            //  $productincart = cart_products::where('user_id', auth()->user()->id)->where('product_id', $idproducttoremove)->firstOrFail();
            $productincart = cart_products::where('user_id', 1)->where('product_id', $idproducttoremove)->firstOrFail();
            //* Verificar que la cantidad en el carrito sea suficiente para restar
            if ($productincart->quantity >= $quantitytoremove) {

                $productincart->quantity -= $quantitytoremove;

                //* Si la cantidad es 0, eliminar el producto del carrito
                if ($productincart->quantity == 0) {
                    $productincart->delete();
                    return response()->json(['message' => 'El producto se ha eliminado del carrito.'], 200);
                }

                //* Guardar la nueva cantidad
                $productincart->save();
                //* mensaje caso exitoso
                return response()->json(['message' => 'Cantidad actualizada en el carrito.'], 200);
            } else {

                //! mensaje caso resultado negativo
                return response()->json(['error' => 'Cantidad a remover es mayor a la cantidad en el carrito.'], 400);
            }
        } catch (\Exception $e) {

            //! mensaje de error
            return response()->json(['error' => 'Ocurrió un error al intentar actualizar el carrito.'], 500);
        }
    }


    //todo    Metodo para remover un producto del carrito 
    public function removeProductFromCart(Request $request)
    {
        try {
            $idproducttoremove = $request->input('idproducttoremove');
            //* Buscar el producto en el carrito 
            //  $productincart = cart_products::where('user_id', auth()->user()->id)->where('product_id', $idproducttoremove)->firstOrFail();
            $productincart = cart_products::where('user_id', 1)->where('product_id', $idproducttoremove)->firstOrFail();

            $productincart->delete();
            return response()->json(['message' => 'El producto se ha eliminado del carrito.'], 200);
        } catch (\Exception $e) {

            //! mensaje de error
            return response()->json(['error' => 'Ocurrió un error al intentar actualizar el carrito.'], 500);
        }
    }

    //todo    Metodo para remover un producto del carrito 
    public function removeAllProductsFromCart()
    {
        try {
            //* Buscar el producto en el carrito 
            //  $productincart = cart_products::where('user_id', auth()->user()->id)->where('product_id', $idproducttoremove)->firstOrFail();
            $productincart = cart_products::where('user_id', 1)->get();

            foreach ($productincart as $product) {
                $product->delete();
            }
            return response()->json(['message' => 'El producto se ha eliminado del carrito.'], 200);
        } catch (\Exception $e) {

            //! mensaje de error
            return response()->json(['error' => 'Ocurrió un error al intentar actualizar el carrito.'], 500);
        }
    }


    public function create()
    {
        //
    }



    public function store(Request $request)
    {
        //
    }


    public function show(string $id)
    {
        //
    }


    public function edit(string $id)
    {
        //
    }


    public function update(Request $request, string $id)
    {
        //
    }


    public function destroy(string $id)
    {
        //
    }
}
