<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';

    // Definir la relación con OrderItem
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');

    }

    public function orderTransactions()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'transaction_id');

    }


    
  
}
