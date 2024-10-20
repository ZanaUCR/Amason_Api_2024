<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transaction extends Model
{
    use HasFactory;

        // Definir la relación con PaymentMethod
        public function paymentMethod()
        {
            return $this->belongsTo(PaymentMethod::class, 'payment_method_id', 'id');
        }
    
        // Definir la relación con Order (si también tienes un modelo Order)
      
        public function transactionOrder()
        {
           return $this->hasOne(Order::class, 'transaction_id', 'transaction_id');
        }
}
