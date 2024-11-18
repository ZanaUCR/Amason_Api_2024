<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class ProductController extends Controller
{
    // Obtener productos por tienda
    public function getProductsByStore($storeId)
{
    $products = Product::where('id_store', $storeId)
        ->with('images', 'category') // Incluir la categoría en la consulta
        ->select('product_id', 'name', 'price', 'category_id', 'description', 'stock')
        ->get();

    // Asignar la primera imagen o una imagen por defecto y agregar el nombre de la categoría
    $products->each(function ($product) {
        $product->image = $product->images->first()->image_path ?? 'default_image_path';
        $product->category_name = $product->category->name ?? 'Categoría desconocida'; // Añadir el nombre de la categoría
        unset($product->images);  // Remover las imágenes del resultado
        unset($product->category);  // Remover los detalles de la categoría
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
        'image_links' => 'sometimes|array', // Aceptamos un array de links de imágenes
        'image_links.*' => 'sometimes|url' // Cada link debe ser una URL válida
    ], [
        'name.required' => 'El nombre del producto es obligatorio.',
        'description.required' => 'La descripción es obligatoria.',
        'price.required' => 'El precio es obligatorio.',
        'stock.required' => 'El stock es obligatorio.',
        'category_id.required' => 'La categoría es obligatoria.',
        'id_store.required' => 'La tienda es obligatoria.',
        'image_links.*.url' => 'Cada link de imagen debe ser una URL válida.',
    ]);

    // Si la validación falla, retornar errores
    if ($validator->fails()) {
        return response()->json(['message' => 'Falló la validación', 'errors' => $validator->errors()], 400);
    }
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

        // Si el producto fue creado correctamente, agregar las imágenes
        if ($product && $request->has('image_links')) {
            $this->addProductImages($product->product_id, $request->image_links);  // Usamos product_id
        }

        return response()->json(['message' => 'Producto creado con éxito'], 201);
    } catch (\Exception $e) {
        // Capturar cualquier excepción y retornar un mensaje
        return response()->json(['error' => 'Error al crear el producto', 'details' => $e->getMessage()], 500);
    }
}

// Método para agregar las imágenes a la tabla product_images
private function addProductImages($productId, $imageLinks)
{
    if (!empty($imageLinks)) {
        foreach ($imageLinks as $link) {
            ProductImage::create([
                'product_id' => $productId,  // Usamos product_id para referenciar al producto
                'image_path' => $link,
            ]);
        }
    }
}


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
        'image_links' => 'sometimes|array', // Para los enlaces de imágenes
        'image_links.*' => 'sometimes|url', // Validar que los enlaces sean URLs válidas
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 400);
    }

    try {
        // Actualizar el producto con los datos validados
        $product->update($validator->validated());

        // Llamar al método separado si se han proporcionado nuevos enlaces de imágenes
        if ($request->has('image_links')) {
            $this->updateProductImages($product, $request->image_links);
        }

        // Cargar nuevamente las imágenes y la categoría para devolver al frontend
        $updatedProduct = $product->load(['images', 'category']);

        return response()->json(['message' => 'Producto actualizado con éxito', 'product' => $updatedProduct], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error al actualizar el producto', 'details' => $e->getMessage()], 500);
    }
}

// Método para actualizar enlaces de imágenes
public function updateProductImages($product, $imageLinks)
{
    // Verificar si hay imágenes existentes y eliminarlas
    if ($product->images()->exists()) {
        $product->images()->delete(); // Eliminar las imágenes anteriores
    }

    // Guardar los nuevos enlaces de imágenes
    foreach ($imageLinks as $link) {
        ProductImage::create([
            'product_id' => $product->product_id, // Asegurarse de usar product_id
            'image_path' => $link,
        ]);
    }
}




    public function deleteProduct($product_id)
    {
        // Buscar el producto por su ID
        $product = Product::findOrFail($product_id);
    
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
