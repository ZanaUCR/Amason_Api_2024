<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Login
 * 
 * @property int $login_id
 * @property string $email
 * @property string $password
 * @property Carbon $registration_date
 * 
 * @property Collection|User[] $users
 * @property User $user
 *
 * @package App\Models
 */
class Login extends Model
{
	protected $table = 'login';
	protected $primaryKey = 'login_id';
	public $timestamps = false;

	protected $casts = [
		'registration_date' => 'datetime'
	];

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'email',
		'password',
		'registration_date'
	];

	public function users()
	{
		return $this->hasMany(User::class);
	}

	public function user()
	{
		return $this->hasOne(User::class, 'user_id');
	}
}
