<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Order
 * 
 * @property int $order_id
 * @property int $user_id
 * @property int $order_payments_id
 * @property float $total_amount
 * @property int $status
 * 
 * @property User $user
 * @property Collection|OrderItem[] $order_items
 * @property Collection|Transaction[] $transactions
 *
 * @package App\Models
 */
class Order extends Model
{
	protected $table = 'orders';
	protected $primaryKey = 'order_id';
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'order_payments_id' => 'int',
		'total_amount' => 'float',
		'status' => 'int'
	];

	protected $fillable = [
		'user_id',
		'order_payments_id',
		'total_amount',
		'status'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function order_items()
	{
		return $this->hasMany(OrderItem::class);
	}

	public function transactions()
	{
		return $this->hasMany(Transaction::class);
	}
}
