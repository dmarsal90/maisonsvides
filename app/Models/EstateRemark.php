<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EstateRemark extends Model
{
	use HasFactory;
	use SoftDeletes;

	protected $dates = ['deleted_at'];

	// Set the table
	protected $table = 'estate_remarks';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'id',
		'estate_id',
		'interior_state',
		'exterior_state',
		'district_state',
		'interior_highlights',
		'exterior_highlights',
		'interior_weak_point',
		'exterior_weak_point',
		'desires_to_sell',
		'his_estimate',
		'accept_price',
		'agent_notice'
	];
}
