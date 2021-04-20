<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstateLog extends Model
{
	use HasFactory;

	// Set the table
	protected $table = 'estate_logs';
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
		'user_id',
		'old_value',
		'new_value',
		'field'
	];
}