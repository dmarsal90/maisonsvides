<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstateOffre extends Model
{
	use HasFactory;
	// Set the table
	protected $table = 'estate_offers';
	// Disable timestamps
	public $timestamps = false;
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'estate_id',
		'price_seller',
		'price_wesold',
		'price_market',
		'other_offer',
		'notaire',
		'condition_offer',
		'validity',
		'textadded',
		'pdf'
	];
}
