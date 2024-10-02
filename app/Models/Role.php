<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Role
 * 
 * @property int $role_id
 * @property string $name
 * 
 * @property Collection|User[] $users
 *
 * @package App\Models
 */
class Role extends Model
{
	protected $table = 'role';
	protected $primaryKey = 'role_id';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'role_id' => 'int'
	];

	protected $fillable = [
		'name'
	];

	public function users()
	{
		return $this->belongsToMany(User::class);
	}
}
