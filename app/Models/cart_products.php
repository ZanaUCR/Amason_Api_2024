<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cart_products extends Model
{
    use HasFactory;

   
    protected $table = 'cart_products';

 
    protected $fillable = ['quantity', 'user_id', 'product_id'];

   
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

   
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
