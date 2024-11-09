<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $primaryKey = 'order_id';
protected $fillable = ['status', 'payment_method_id'];
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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id', 'payment_method_id');
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }
    


    
  
}
