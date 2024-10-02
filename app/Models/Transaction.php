<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Transaction
 * 
 * @property int $transaction_id
 * @property int $order_id
 * @property int $payment_method_id
 * @property Carbon $purchase_date
 * 
 * @property Order $order
 * @property PaymentMethod $payment_method
 *
 * @package App\Models
 */
class Transaction extends Model
{
	protected $table = 'transaction';
	protected $primaryKey = 'transaction_id';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'transaction_id' => 'int',
		'order_id' => 'int',
		'payment_method_id' => 'int',
		'purchase_date' => 'datetime'
	];

	protected $fillable = [
		'order_id',
		'payment_method_id',
		'purchase_date'
	];

	public function order()
	{
		return $this->belongsTo(Order::class);
	}

	public function payment_method()
	{
		return $this->hasOne(PaymentMethod::class, 'id');
	}
}
