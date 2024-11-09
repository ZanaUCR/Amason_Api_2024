<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


class StoreController extends Controller
{

    public function createStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'seller_id' => 'required',
            'store_name' => 'required',
            'description' => 'required',
            'email' => 'required|email|unique:stores',


        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }
        $store = Store::create(

            [
                'seller_id' => $request->seller_id,
                'store_name' => $request->store_name,
                'description' => $request->description,
                'email' => $request->email,

            ]
        );
        if (!$store) {
            $data = [
                'message' => 'Error en la creacion del vendedor',
                'status' => 400
            ];
            return response()->json($data, 400);
        }
        $data = [
            'vendedor' => $store,
            'status' => 201
        ];
        return response()->json($data, 201);

    }
    public function storesBySellerId($sellerId)
    {

        $stores = Store::where('seller_id', $sellerId)->select('Id', 'store_name', 'description','email')->get();

        return response()->json($stores, 200);
    }
    public function storesById($Id)
    {

        $stores = Store::where('id', $Id)->select('id', 'store_name', 'description','email')->get();

        return response()->json($stores, 200);
    }
    public function deleteStore($store_id)
    {
      
        $store = Store::findOrFail($store_id);
        // Eliminar productos asociados a la tienda, si los hay
        foreach ($store->products as $product) {
            // Eliminar imágenes del producto
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }
        
            $product->delete();
        }

        $store->delete();

        return response()->json(['message' => 'Tienda eliminada con éxito'], 200);
    }
    public function updateStore(Request $request, $id)
    {
        
        $validatedData = $request->validate([
            'store_name' => 'string',
            'description' => 'string',
            'email' => 'string|email',
        ]);

        $store = Store::findOrFail($id);

        // Actualizar los campos de la tienda solo si se proporcionaron nuevos valores
        if ($request->has('store_name'))
            $store->store_name = $validatedData['store_name'];
        if ($request->has('description'))
            $store->description = $validatedData['description'];
        if ($request->has('email'))
            $store->email = $validatedData['email'];
     
        $store->save();

 
        return response()->json(['message' => 'Tienda actualizada con éxito', 'store' => $store], 200);
    }
}
