<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 * 
 * @property int $product_id
 * @property int $category_id
 * @property string|null $name
 * @property string|null $description
 * @property float $price
 * @property int $stock
 * 
 * @property Store $store
 * @property Category $category
 * @property Collection|OrderItem[] $order_items
 * @property Collection|Review[] $reviews
 *
 * @package App\Models
 */
class Product extends Model
{
	protected $table = 'products';
	protected $primaryKey = 'product_id';
	public $timestamps = false;

	protected $casts = [
		'category_id' => 'int',
		'price' => 'float',
		'stock' => 'int'
	];

	protected $fillable = [
		'category_id',
		'name',
		'description',
		'price',
		'stock'
	];

	public function store()
	{
		return $this->belongsTo(Store::class, 'product_id');
	}

	public function category()
	{
		return $this->belongsTo(Category::class);
	}

	public function order_items()
	{
		return $this->hasMany(OrderItem::class);
	}

	public function reviews()
	{
		return $this->hasMany(Review::class);
	}
}
