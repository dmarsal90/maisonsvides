<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToEstatesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('estate_details', function (Blueprint $table) {
            $table->unsignedBigInteger('seller_id')->nullable();
        //     $table->unsignedBigInteger('offer_id')->nullable();
            $table->string('seller_name')->nullable();
            $table->string('seller_phone')->nullable();
            $table->string('seller_email')->nullable();
            $table->string('town_planning')->nullable();
            $table->boolean('more_habitations')->nullable();
            $table->integer('rooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->text('estate_description')->nullable();
            $table->string('estate_street')->nullable();
            $table->boolean('jardin')->nullable();
            $table->integer('gaz')->nullable();
            $table->integer('electrique')->nullable();
            $table->text('details_commentaire')->nullable();
            $table->text('interior_state')->nullable();
            $table->text('exterior_state')->nullable();
            $table->text('district_state')->nullable();
            $table->text('interior_highlights')->nullable();
            $table->text('exterior_highlights')->nullable();
            $table->text('interior_weak_point')->nullable();
            $table->text('exterior_weak_point')->nullable();
            $table->integer('desires_to_sell')->nullable();
            $table->text('agent_notice')->nullable();
            $table->integer('details_state_interior')->nullable();
            $table->integer('details_state_exterior')->nullable();

           // $table->foreign('seller_id')->references('id')->on('sellers');
            // $table->foreign('offer_id')->references('id')->on('estate_offers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('estate_details', function (Blueprint $table) {
            $table->dropColumn([
                 'seller_id',
                // 'offer_id',
                'seller_name',
                'seller_phone',
                'seller_email',
                'town_planning',
                'more_habitations',
                'rooms',
                'bathrooms',
                'estate_description',
                'estate_street',
                'jardin',
                'gaz',
                'electrique',
                'details_commentaire',
                'interior_state',
                'exterior_state',
                'district_state',
                'interior_highlights',
                'exterior_highlights',
                'interior_weak_point',
                'exterior_weak_point',
                'desires_to_sell',
                'agent_notice',
                'details_state_interior',
                'details_state_exterior',
            ]);
        });
    }
}
