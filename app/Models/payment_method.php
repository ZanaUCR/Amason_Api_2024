<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class payment_method extends Model
{
    use HasFactory;

    protected $table = 'payment_method';

    // Definir la relación con Transaction
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'payment_method_id', 'id');
    }
}
