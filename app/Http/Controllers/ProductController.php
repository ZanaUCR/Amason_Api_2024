<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    // Obtener productos por tienda
public function getProductsByStore($storeId)
{
    $products = Product::where('id_store', $storeId)
        ->with('images')
        ->select('id', 'name', 'price') // Añadir 'id' a la selección
        ->get();

    // Asignar la primera imagen o una imagen por defecto
    $products->each(function ($product) {
        $product->image = $product->images->first()->image_path ?? 'default_image_path';
        unset($product->images);  // Remover las imágenes del resultado
    });

    return response()->json($products, 200);
}

    // Agregar un nuevo producto
    public function store(Request $request)
    {

        // Validar los datos entrantes con mensajes personalizados
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:1',
            'category_id' => 'required|exists:categories,id',
            'id_store' => 'required|exists:stores,id',
            'images.*' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'name.required' => 'El nombre del producto es obligatorio.',
            'description.required' => 'La descripción es obligatoria.',
            'price.required' => 'El precio es obligatorio.',
            'stock.required' => 'El stock es obligatorio.',
            'category_id.required' => 'La categoría es obligatoria.',
            'id_store.required' => 'La tienda es obligatoria.',
            'images.*.mimes' => 'Cada imagen debe ser de tipo: jpeg, png, jpg, gif, svg.',
        ]);

        // Si la validación falla, retornar errores
        if ($validator->fails()) {
            return response()->json(['message' => 'Falló la validación', 'errors' => $validator->errors()], 400);
        }

        // Crear el producto
        try {
            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock,
                'category_id' => $request->category_id,
                'id_store' => $request->id_store,
            ]);

           

            // Manejar las imágenes (opcional)
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                    ]);
                }
            }

            return response()->json(['message' => 'Producto creado con éxito'], 201);
        } catch (\Exception $e) {
            // Capturar cualquier excepción y retornar un mensaje
            return response()->json(['error' => 'Error al crear el producto', 'details' => $e->getMessage()], 500);
        }
    }

    // Editar un producto existente
    public function editProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Validar los datos de la actualización
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string|max:1000',
            'price' => 'sometimes|required|numeric|min:0',
            'stock' => 'sometimes|required|integer|min:1',
            'category_id' => 'sometimes|required|exists:categories,id',
            'images.*' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            // Actualizar el producto
            $product->update($validator->validated());

            // Si se han enviado nuevas imágenes, eliminar las anteriores y agregar las nuevas
            if ($request->hasFile('images')) {
                foreach ($product->images as $image) {
                    Storage::disk('public')->delete($image->image_path);
                    $image->delete();
                }

                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                    ]);
                }
            }

            return response()->json(['message' => 'Producto actualizado con éxito', 'product' => $product], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar el producto', 'details' => $e->getMessage()], 500);
        }
    }
    

    public function deleteProduct($id)
    {
        // Buscar el producto por su ID
        $product = Product::findOrFail($id);
    
        // Eliminar imágenes asociadas al producto, si las hay
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }
    
        // Eliminar el producto
        $product->delete();
    
        return response()->json(['message' => 'Producto eliminado con éxito'], 200);
    }


    
    public function getProductsByCategory($categoryId)
{
    // Obtener todos los productos que pertenecen a la categoría especificada
    $products = Product::where('category_id', $categoryId)
        ->with('images') // Obtener imágenes si las tienes relacionadas
        ->get();

    // Asignar la primera imagen o una imagen por defecto
    $products->each(function ($product) {
        $product->image = $product->images->first()->image_path ?? 'default_image_path';
        unset($product->images);  // Remover las imágenes del resultado
    });

    return response()->json($products, 200);
}




}
