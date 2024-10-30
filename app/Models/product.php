<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product extends Model
{protected $primaryKey = 'product_id';
    use HasFactory;

    // Agrega los atributos que se pueden llenar de forma masiva
    protected $fillable = ['name', 'description', 'price', 'stock', 'category_id', 'id_store'];
    protected $primaryKey = 'product_id'; // Si la clave primaria es 'product_id'

    // Relación con la categoría
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Relación con las reseñas
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

        // Product.php
public static function getAllProductsInCategory($categoryId)
{
    return self::where('category_id', $categoryId)->get();
}



    // Relación con la tienda
    public function store()
    {
        return $this->belongsTo(Store::class, 'id_store');
    }

    // Relación con las imágenes del producto
    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    // En Product.php (modelo)
    public static function getCategoriesByProductIds($productIds)
    {
        return self::whereIn('product_id', $productIds)->pluck('category_id')->unique();
    }

    // En Product.php (modelo)
    public static function getRecommendedProducts($categoryIds, $productIds)
    {
        return self::whereIn('category_id', $categoryIds)
                ->whereNotIn('product_id', $productIds)
                ->get();
    }


}
