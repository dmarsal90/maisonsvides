<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstateResolution extends Model
{
	use HasFactory;

	// Set the table
	protected $table = 'estate_resolutions';
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'id',
		'estate_id',
		'user_id',
		'comment'
	];
}
