<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
	use HasFactory;
	// Set the table
	protected $table = 'medias';
	// Disable timestamps
	public $timestamps = false;
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'estate_id',
		'name',
		'file_name',
		'type',
		'size',
		'extension'
	];
}
