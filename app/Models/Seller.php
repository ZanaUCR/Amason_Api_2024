<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Seller
 * 
 * @property int $id
 * @property int|null $cedula
 * 
 * @property Collection|Store[] $stores
 *
 * @package App\Models
 */
class Seller extends Model
{
	protected $table = 'sellers';
	public $timestamps = false;

	protected $casts = [
		'cedula' => 'int'
	];

	protected $fillable = [
		'cedula'
	];

	public function stores()
	{
		return $this->hasMany(Store::class);
	}
}
