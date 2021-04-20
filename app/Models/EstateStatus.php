<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstateStatus extends Model
{
	use HasFactory;

	// Set the table
	protected $table = 'estate_status';
	// Disable timestamps
	public $timestamps = false;
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'id',
		'estate_id',
		'category_id',
		'user_id',
		'start_at',
		'stop_at'
	];
}
