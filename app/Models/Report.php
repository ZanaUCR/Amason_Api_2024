<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Report
 * 
 * @property int $report_id
 * @property int $seller_id
 * @property string|null $report_type
 * @property Carbon|null $first_date
 * @property Carbon|null $last_date
 * @property string|null $information
 * @property Carbon|null $generation_date
 *
 * @package App\Models
 */
class Report extends Model
{
	protected $table = 'report';
	protected $primaryKey = 'report_id';
	public $timestamps = false;

	protected $casts = [
		'seller_id' => 'int',
		'first_date' => 'datetime',
		'last_date' => 'datetime',
		'generation_date' => 'datetime'
	];

	protected $fillable = [
		'seller_id',
		'report_type',
		'first_date',
		'last_date',
		'information',
		'generation_date'
	];
}
