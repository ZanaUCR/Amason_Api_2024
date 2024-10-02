<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Faq
 * 
 * @property int $id
 *
 * @package App\Models
 */
class Faq extends Model
{
	protected $table = 'faqs';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int'
	];
}
