<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order_item extends Model
{
    use HasFactory;

        // Definir la relación con Order
        public function order()
        {
            return $this->belongsTo(Order::class, 'order_id', 'order_id');
        }
}
