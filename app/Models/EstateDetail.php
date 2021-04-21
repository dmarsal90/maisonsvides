<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EstateDetail extends Model
{
	use HasFactory;
	use SoftDeletes;

	protected $dates = ['deleted_at'];

	// Set the table
	protected $table = 'estate_details';

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
		'description',
		'comment',
		'problems',
		'encode',
		'adapte',
		'year_construction',
		'year_renovation',
		'coordinate_x',
		'coordinate_y',
		'peb',
		'price_evaluated',
		'price_market',
		'visit_remarks',
	];
}
