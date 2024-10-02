<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 * 
 * @property int $user_id
 * @property int $login_id
 * @property string $name
 * @property string|null $second_name
 * @property string|null $last_name
 * @property string|null $second_last_name
 * @property int|null $role_id
 * @property Carbon|null $registration_date
 * 
 * @property Login $login
 * @property Collection|Ticket[] $tickets
 * @property Collection|Order[] $orders
 * @property Collection|Review[] $reviews
 * @property Collection|Role[] $roles
 *
 * @package App\Models
 */
class User extends Model
{
	protected $table = 'user';
	protected $primaryKey = 'user_id';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'login_id' => 'int',
		'role_id' => 'int',
		'registration_date' => 'datetime'
	];

	protected $fillable = [
		'login_id',
		'name',
		'second_name',
		'last_name',
		'second_last_name',
		'role_id',
		'registration_date'
	];

	public function login()
	{
		return $this->belongsTo(Login::class, 'user_id');
	}

	public function tickets()
	{
		return $this->belongsToMany(Ticket::class, 'assigned_user_ticket');
	}

	public function orders()
	{
		return $this->hasMany(Order::class);
	}

	public function reviews()
	{
		return $this->hasMany(Review::class);
	}

	public function roles()
	{
		return $this->belongsToMany(Role::class);
	}
}
