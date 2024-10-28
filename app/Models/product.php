<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    use HasFactory;

    // Agrega los atributos que se pueden llenar de forma masiva
    protected $fillable = ['name', 'description', 'price', 'stock', 'category_id', 'id_store'];

    // Relación con la categoría
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relación con las reseñas
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Relación con la tienda
    public function store()
    {
        return $this->belongsTo(Store::class, 'id_store');
    }

    // Relación con las imágenes del producto
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
}
