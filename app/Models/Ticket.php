<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Ticket
 * 
 * @property int $ticket_id
 * @property int $user_id
 * @property string $description
 * @property int|null $priority
 * @property Carbon|null $created_at
 * @property int|null $department
 * 
 * @property Collection|User[] $users
 * @property TicketMessage $ticket_message
 *
 * @package App\Models
 */
class Ticket extends Model
{
	protected $table = 'ticket';
	protected $primaryKey = 'ticket_id';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'ticket_id' => 'int',
		'user_id' => 'int',
		'priority' => 'int',
		'department' => 'int'
	];

	protected $fillable = [
		'user_id',
		'description',
		'priority',
		'department'
	];

	public function users()
	{
		return $this->belongsToMany(User::class, 'assigned_user_ticket');
	}

	public function ticket_message()
	{
		return $this->hasOne(TicketMessage::class);
	}
}
