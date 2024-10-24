<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'payment_method';

    // Definir la relación con Transaction

    public function order()
    {
        return $this->hasMany(Order::class);
    }
    
}
