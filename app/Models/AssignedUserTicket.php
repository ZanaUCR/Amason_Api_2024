<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AssignedUserTicket
 * 
 * @property int $ticket_id
 * @property int $user_id
 * 
 * @property Ticket $ticket
 * @property User $user
 *
 * @package App\Models
 */
class AssignedUserTicket extends Model
{
	protected $table = 'assigned_user_ticket';
	protected $primaryKey = 'ticket_id';
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int'
	];

	protected $fillable = [
		'user_id'
	];

	public function ticket()
	{
		return $this->belongsTo(Ticket::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
