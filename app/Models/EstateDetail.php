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
        'seller_id',
        'seller_name',
        'seller_phone',
        'seller_email',
        'town_planning',
        'more_habitations',
        'rooms',
        'bathrooms',
        'estate_description',
        'estate__street',
        'price_evaluated',
        'jardin',
        'gaz',
        'electrique',
        'details__commentaire',
        'interior_state',
        'exterior_state',
        'district_state',
        'surface',
        'interior_highlights',
        'exterior_highlights',
        'interior_weak_point',
        'exterior_weak_point',
        'desires_to_sell',
        'agent_notice',
        'details__state_interior',
        'details__state_exterior'
	];
}
