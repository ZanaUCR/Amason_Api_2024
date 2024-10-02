<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class OrderItem
 * 
 * @property int $id
 * @property int $product_id
 * @property int $order_id
 * @property int $quantity
 * @property float $price_at_purchase
 * 
 * @property Order $order
 * @property Product $product
 *
 * @package App\Models
 */
class OrderItem extends Model
{
	protected $table = 'order_items';
	public $timestamps = false;

	protected $casts = [
		'product_id' => 'int',
		'order_id' => 'int',
		'quantity' => 'int',
		'price_at_purchase' => 'float'
	];

	protected $fillable = [
		'product_id',
		'order_id',
		'quantity',
		'price_at_purchase'
	];

	public function order()
	{
		return $this->belongsTo(Order::class);
	}

	public function product()
	{
		return $this->belongsTo(Product::class);
	}
}
