<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar de forma masiva.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'seller_id',
        'store_name',
        'description',
        'email',
        'logo',
    ];

    /**
     * Relación con el vendedor (tabla 'users')
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * Relación con los productos de la tienda
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'id_store');
    }
    
}
