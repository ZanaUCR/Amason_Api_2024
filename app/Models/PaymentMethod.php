<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PaymentMethod
 * 
 * @property int $id
 * @property string $payment_method
 * 
 * @property Transaction $transaction
 *
 * @package App\Models
 */
class PaymentMethod extends Model
{
	protected $table = 'payment_method';
	public $timestamps = false;

	protected $fillable = [
		'payment_method'
	];

	public function transaction()
	{
		return $this->belongsTo(Transaction::class, 'id');
	}
}
