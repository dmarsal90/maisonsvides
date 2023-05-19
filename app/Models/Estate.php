<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estate extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    // Disable timestamps
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'reference',
        'name',
        'type_estate',
        'category',
        'visit_date_at',
        'main_photo',
        'street',
        'number',
        'box',
        'code_postal',
        'city',
        'seller',
        'when_want_sell',
        'want_tenant_after_sell',
        'want_buy_wesold',
        'agent',
        'notary',
        'estimate',
        'market',
        'type_of_sale',
        'attempt_via_agency',
        'attempt_via_client',
        'agency_name',
        'price_published_agence',
        'date_of_sale_agence',
        'price_published_himself',
        'date_of_sale_himself',
        'information_additional',
        'date_send_reminder',
        'send_reminder_half_past_eight',
        'rdv',
        'surface',
        'garden',
        'terrase',
        'garage',
        'town_planning',
        'more_habitations',
        'number_bathroom',
        'number_rooms',
        'number_gas',
        'number_electric',
        'state_interior',
        'state_exterior',
        'construction',
        'renovation'
    ];

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }
}
