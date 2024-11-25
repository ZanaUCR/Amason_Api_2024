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
            if ($product->images->first()) {
                $imagePath = $product->images->first()->image_path;
    
                // Verificar si el image_path ya es un enlace completo
                if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
                    $product->image = $imagePath; // Usar directamente si es una URL
                } else {
                    $product->image = asset('storage/' . $imagePath); // Generar la URL si no es completa
                }
            } else {
                $product->image = asset('default_image_path'); // Cambia 'default_image_path' a la ruta real de la imagen por defecto
            }
    
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
        'images.*' => 'nullable|file|mimes:jpg,png,jpeg|max:2048', // Validación para múltiples imágenes
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

       // Si se suben imágenes, procesarlas
       if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $filePath = $image->store('products_images', 'public'); // Guardar en el almacenamiento público
            ProductImage::create([
                'product_id' => $product->product_id,
                'image_path' => $filePath,
            ]);
        }
    }

    return response()->json(['message' => 'Producto creado con éxito', 'product' => $product], 201);
} catch (\Exception $e) {
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

public function uploadImage(Request $request)
{
    // Validar el archivo
    $request->validate([
        'image' => 'required|file|mimes:jpg,jpeg,png|max:2048', // Máximo 2MB
    ]);

    try {
        // Almacenar la imagen en el almacenamiento público
        $filePath = $request->file('image')->store('products_images', 'public');

        // Retornar la ruta de la imagen
        return response()->json(['url' => Storage::url($filePath)], 201);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error al subir la imagen', 'details' => $e->getMessage()], 500);
    }
}

public function editProduct(Request $request, $id)
{
    // Validar los datos entrantes
    $validator = Validator::make($request->all(), [
        'name' => 'nullable|string|max:255',
        'description' => 'nullable|string|max:1000',
        'price' => 'nullable|numeric|min:0',
        'stock' => 'nullable|integer|min:1',
        'category_id' => 'nullable|exists:categories,id',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 400);
    }

    try {
        // Encontrar y actualizar el producto
        $product = Product::findOrFail($id);
        $product->update($validator->validated());

        return response()->json(['message' => 'Producto actualizado con éxito', 'product' => $product], 200);

    } catch (\Exception $e) {
        return response()->json(['error' => 'Error al actualizar el producto', 'details' => $e->getMessage()], 500);
    }
}

public function updateProductImages(Request $request, $id)
{
    $product = Product::findOrFail($id);

    // Validar las imágenes
    $validator = Validator::make($request->all(), [
        'images.*' => 'required|file|mimes:jpg,png,jpeg|max:2048', // Validar imágenes
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 400);
    }

    try {
        // Eliminar imágenes existentes
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        // Guardar nuevas imágenes
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filePath = $image->store('products_images', 'public');
                ProductImage::create([
                    'product_id' => $product->product_id,
                    'image_path' => $filePath,
                ]);
            }
        }

        return response()->json(['message' => 'Imágenes actualizadas con éxito'], 200);

    } catch (\Exception $e) {
        return response()->json(['error' => 'Error al actualizar las imágenes', 'details' => $e->getMessage()], 500);
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
    
        // Asignar la primera imagen con URL completa o una imagen por defecto
        $products->each(function ($product) {
            if ($product->images->first()) {
                $imagePath = $product->images->first()->image_path;
    
                // Verificar si el image_path ya es un enlace completo
                if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
                    $product->image = $imagePath; // Usar directamente si es una URL
                } else {
                    $product->image = asset('storage/' . $imagePath); // Generar la URL si no es completa
                }
            } else {
                $product->image = asset('default_image_path'); // Cambia 'default_image_path' a la ruta real de la imagen por defecto
            }
    
            unset($product->images); // Remover las imágenes del resultado
        });
    
        return response()->json($products, 200);
    }
    



    
    public function searchProducts(Request $request)
{
    $query = Product::query();

    // Filtrar por nombre (si se proporciona)
    if ($request->has('name') && !empty($request->name)) {
        $query->where('name', 'LIKE', '%' . $request->name . '%');
    }

    // Filtrar por categoría (si se proporciona)
    if ($request->has('category_id') && !empty($request->category_id)) {
        $query->where('category_id', $request->category_id);
    }

    // Incluir imágenes y categorías
    $products = $query->with('images', 'category')
        ->select('product_id', 'name', 'price', 'category_id', 'description', 'stock')
        ->get();

    // Formatear los resultados (incluir imágenes)
    $products->each(function ($product) {
        $product->image = $product->images->first() 
            ? asset('storage/' . $product->images->first()->image_path)
            : asset('default_image_path');

        $product->category_name = $product->category->name ?? 'Categoría desconocida';
        unset($product->images, $product->category);
    });

    return response()->json($products, 200);
}



public function createVariation(Request $request, $productId)
{
    // Validar los datos de entrada
    $validator = Validator::make($request->all(), [
        'type' => 'required|string|max:255',
        'options' => 'required|array|min:1',
        'options.*' => 'string|max:255',
    ], [
        'type.required' => 'El tipo de variación es obligatorio.',
        'options.required' => 'Debe proporcionar al menos una opción para la variación.',
        'options.array' => 'Las opciones deben enviarse como un arreglo.',
    ]);

    // Manejar errores de validación
    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 400);
    }

    try {
        // Encontrar el producto por ID
        $product = Product::findOrFail($productId);

        // Decodificar las variaciones existentes o inicializar un arreglo vacío
        $variation = json_decode($product->variation, true) ?? [];

        // Agregar la nueva variación
        $variation[] = [
            'type' => $request->type,
            'options' => $request->options,
        ];

        // Actualizar el campo de variaciones en formato JSON
        $product->variation = json_encode($variation, JSON_UNESCAPED_UNICODE);
        $product->save();

        return response()->json([
            'message' => 'Variación agregada con éxito',
            'product' => $product,
            'variation' => $variation,
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Error al agregar la variación',
            'details' => $e->getMessage(),
        ], 500);
    }
}


public function updateVariation(Request $request, $productId)
{
    // Validar los datos de entrada
    $validator = Validator::make($request->all(), [
        'type' => 'required|string|max:255',
        'options' => 'required|array|min:1',
        'options.*' => 'string|max:255',
    ], [
        'type.required' => 'El tipo de variación es obligatorio.',
        'options.required' => 'Debe proporcionar al menos una opción para la variación.',
        'options.array' => 'Las opciones deben enviarse como un arreglo.',
    ]);

    // Manejar errores de validación
    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 400);
    }

    try {
        // Encontrar el producto por ID
        $product = Product::findOrFail($productId);

        // Decodificar las variaciones existentes
        $variations = json_decode($product->variation, true) ?? [];

        // Buscar la variación por su tipo
        $found = false;
        foreach ($variations as &$variation) {
            if ($variation['type'] === $request->type) {
                // Actualizar las opciones de la variación encontrada
                $variation['options'] = $request->options;
                $found = true;
                break;
            }
        }

        if (!$found) {
            return response()->json([
                'error' => 'No se encontró una variación con el tipo especificado.',
            ], 404);
        }

        // Guardar las variaciones actualizadas
        $product->variation = json_encode($variations, JSON_UNESCAPED_UNICODE);
        $product->save();

        return response()->json([
            'message' => 'Variación actualizada con éxito',
            'product' => $product,
            'variation' => $variations,
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Error al actualizar la variación',
            'details' => $e->getMessage(),
        ], 500);
    }
}

public function deleteVariation(Request $request, $productId)
{
    // Validar los datos de entrada
    $validator = Validator::make($request->all(), [
        'type' => 'required|string|max:255',
    ], [
        'type.required' => 'El tipo de variación es obligatorio.',
    ]);

    // Manejar errores de validación
    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 400);
    }

    try {
        // Encontrar el producto por ID
        $product = Product::findOrFail($productId);

        // Decodificar las variaciones existentes
        $variations = json_decode($product->variation, true) ?? [];

        // Filtrar las variaciones para eliminar la que coincide con el tipo
        $updatedVariations = array_filter($variations, function ($variation) use ($request) {
            return $variation['type'] !== $request->type;
        });

        // Verificar si se eliminó alguna variación
        if (count($variations) === count($updatedVariations)) {
            return response()->json([
                'error' => 'No se encontró una variación con el tipo especificado.',
            ], 404);
        }

        // Guardar las variaciones actualizadas
        $product->variation = json_encode(array_values($updatedVariations), JSON_UNESCAPED_UNICODE);
        $product->save();

        return response()->json([
            'message' => 'Variación eliminada con éxito',
            'product' => $product,
            'variations' => $updatedVariations,
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Error al eliminar la variación',
            'details' => $e->getMessage(),
        ], 500);
    }
}

public function getVariations($productId)
{
    try {
        // Encontrar el producto por ID
        $product = Product::findOrFail($productId);

        // Decodificar las variaciones existentes
        $variations = json_decode($product->variation, true) ?? [];

        return response()->json($variations, 200);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Error al obtener las variaciones',
            'details' => $e->getMessage(),
        ], 500);
    }
}



}
