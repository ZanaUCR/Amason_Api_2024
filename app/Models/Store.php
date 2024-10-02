<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Store
 * 
 * @property int $id
 * @property int|null $seller_id
 * @property int|null $location_id
 * @property string $store_name
 * @property string|null $description
 * @property string $email
 * @property string $logo
 * 
 * @property Seller|null $seller
 * @property Product $product
 * @property Collection|RangeArea[] $range_areas
 *
 * @package App\Models
 */
class Store extends Model
{
	protected $table = 'stores';
	public $timestamps = false;

	protected $casts = [
		'seller_id' => 'int',
		'location_id' => 'int',
		'logo' => 'binary'
	];

	protected $fillable = [
		'seller_id',
		'location_id',
		'store_name',
		'description',
		'email',
		'logo'
	];

	public function seller()
	{
		return $this->belongsTo(Seller::class);
	}

	public function product()
	{
		return $this->hasOne(Product::class, 'product_id');
	}

	public function range_areas()
	{
		return $this->hasMany(RangeArea::class);
	}
}
