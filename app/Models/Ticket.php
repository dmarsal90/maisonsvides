<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'name',
        'email',
        'subject',
        'comment',
        'isSolved',
        'agent_id'
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }
}
