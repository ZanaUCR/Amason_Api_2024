<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


use App\Models\Role;
use App\Models\Review;
use App\Models\Order;
use Illuminate\Database\Eloquent\Relations\HasMany;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'address', // Añade este atributo
        'city',    // Añade este atributo
        'postal_code', // Añade este atributo
        'country',     // Añade este atributo
        
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole($roleName)
    {
        return $this->roles()->where('name', $roleName)->exists();
    }
    
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Relación con el modelo Order
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function getPurchasedProductsInCategory($categoryId)
    {
        return $this->orders()
            ->whereHas('orderItems.product', function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->with(['orderItems' => function ($query) use ($categoryId) {
                $query->whereHas('product', function ($query) use ($categoryId) {
                    $query->where('category_id', $categoryId);
                });
            }, 'orderItems.product.images'])
            ->get()
            ->flatMap(function ($order) {
                return $order->orderItems->map(function ($orderItem) {
                    $product = $orderItem->product;
                    $product->setRelation('images', $product->images);
                    return $product;
                });
            })
            ->values(); // Eliminamos 'unique' para evitar que limite los resultados a uno solo
    }
    
    
    


// User.php
public static function getUserById($id)
{
    return self::findOrFail($id); // Aquí manejamos la consulta específica
}

    // Relación con las devoluciones de pedidos
    public function orderReturns()
    {
        return $this->hasMany(OrderReturn::class);
    }

}
