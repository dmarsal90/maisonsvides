<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EstateRealEstate extends Model
{
	use HasFactory;
	use SoftDeletes;

	protected $dates = ['deleted_at'];

	// Set the table
	protected $table = 'estate_realestate';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'id',
		'estate_id',
		'realestate_id',
		'refrence',
		'url',
		'put_online',
		'price'
	];
}
