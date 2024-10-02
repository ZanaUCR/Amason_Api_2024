<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RoleUser
 * 
 * @property int $role_id
 * @property int $user_id
 * 
 * @property User $user
 * @property Role $role
 *
 * @package App\Models
 */
class RoleUser extends Model
{
	protected $table = 'role_user';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'role_id' => 'int',
		'user_id' => 'int'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function role()
	{
		return $this->belongsTo(Role::class);
	}
}
