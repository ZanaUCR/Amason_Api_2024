<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Inventory
 * 
 * @property int $inventory_id
 *
 * @package App\Models
 */
class Inventory extends Model
{
	protected $table = 'inventory';
	protected $primaryKey = 'inventory_id';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'inventory_id' => 'int'
	];
}
