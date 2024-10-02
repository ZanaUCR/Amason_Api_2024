<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class VariantProduct
 * 
 * @property int $variant_id
 * @property string|null $brand
 * @property string|null $state
 * @property int|null $stock
 *
 * @package App\Models
 */
class VariantProduct extends Model
{
	protected $table = 'variant_product';
	protected $primaryKey = 'variant_id';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'variant_id' => 'int',
		'stock' => 'int'
	];

	protected $fillable = [
		'brand',
		'state',
		'stock'
	];
}
