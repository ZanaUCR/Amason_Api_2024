<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_package',
        'claim_type',
        'subject',
        'description',
        'file',
        'notify_by',
        'user_id' // This is the foreign key
    ];

    public function user()
{
    return $this->belongsTo(User::class);
}

}