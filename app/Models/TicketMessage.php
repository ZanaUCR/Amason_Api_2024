<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TicketMessage
 * 
 * @property int $ticket_id
 * @property int|null $description
 * 
 * @property Ticket $ticket
 *
 * @package App\Models
 */
class TicketMessage extends Model
{
	protected $table = 'ticket_messages';
	protected $primaryKey = 'ticket_id';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'ticket_id' => 'int',
		'description' => 'int'
	];

	protected $fillable = [
		'description'
	];

	public function ticket()
	{
		return $this->belongsTo(Ticket::class);
	}
}
