<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstateEvent extends Model
{
	use HasFactory;

	// Set the table
	protected $table = 'estates_events';
	// Disable timestamps
	public $timestamps = false;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'estate_id',
		'event_id',
		'user_id',
		'seller_id',
		'confirmed'
	];
}
