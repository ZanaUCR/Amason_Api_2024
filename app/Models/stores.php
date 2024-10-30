<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class stores extends Model
{
    use HasFactory;

    // Define el nombre de la tabla si no sigue la convención de pluralización
    protected $table = 'stores';

    // Campos que se pueden asignar en masa
    protected $fillable = [
        'seller_id',
        'location_id',
        'store_name',
        'description',
        'email',
        'logo'
    ];

    // Relación con la tabla users
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id', 'id');
    }

    // Relación con la tabla products
    public function products()
    {
        return $this->hasMany(Product::class, 'id_store', 'id');
    }

    // Relación con la tabla range_areas (si se requiere)
    public function rangeAreas()
    {
        return $this->hasMany(RangeArea::class, 'store_id', 'id');
    }
}
