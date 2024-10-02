<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Alert
 * 
 * @property int $alert_id
 *
 * @package App\Models
 */
class Alert extends Model
{
	protected $table = 'alert';
	protected $primaryKey = 'alert_id';
	public $timestamps = false;
}
