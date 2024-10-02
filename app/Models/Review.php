<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Review
 * 
 * @property int $review_id
 * @property int $user_id
 * @property int $product_id
 * @property int $calification
 * @property string $comment
 * @property Carbon|null $review_date
 * 
 * @property Product $product
 * @property User $user
 *
 * @package App\Models
 */
class Review extends Model
{
	protected $table = 'reviews';
	protected $primaryKey = 'review_id';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'review_id' => 'int',
		'user_id' => 'int',
		'product_id' => 'int',
		'calification' => 'int',
		'review_date' => 'datetime'
	];

	protected $fillable = [
		'user_id',
		'product_id',
		'calification',
		'comment',
		'review_date'
	];

	public function product()
	{
		return $this->belongsTo(Product::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
