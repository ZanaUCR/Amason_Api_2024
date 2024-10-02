<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RangeArea
 * 
 * @property int $id
 * @property int $store_id
 * @property string|null $country
 * 
 * @property Store $store
 *
 * @package App\Models
 */
class RangeArea extends Model
{
	protected $table = 'range_areas';
	public $timestamps = false;

	protected $casts = [
		'store_id' => 'int'
	];

	protected $fillable = [
		'store_id',
		'country'
	];

	public function store()
	{
		return $this->belongsTo(Store::class);
	}
}
