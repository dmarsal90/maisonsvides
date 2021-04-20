<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EstateReminder extends Model
{
	use HasFactory;
	use SoftDeletes;

	protected $dates = ['deleted_at'];

	// Set the table
	protected $table = 'estate_reminder';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'id',
		'estate_id',
		'user_id',
		'name_reminder',
		'content',
		'sent',
		'hide',
		'next_reminder'
	];
}
