<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateEstatesTable extends Migration
{
    public function up()
    {
        Schema::create('estates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamp('reference')->useCurrent();
            $table->string('name');
            $table->string('type_estate');
            $table->bigInteger('category');
            $table->string('visit_date_at')->nullable();
            $table->string('main_photo');
            $table->string('street');
            $table->integer('number');
            $table->integer('box');
            $table->integer('code_postal');
            $table->string('city');
            $table->bigInteger('seller');
            $table->longText('when_want_sell');
            $table->longText('want_tenant_after_sell');
            $table->longText('want_buy_wesold');
            $table->bigInteger('agent');
            $table->bigInteger('notary');
            $table->double('estimate');
            $table->double('market');
            $table->string('type_of_sale');
            $table->boolean('attempt_via_agency');
            $table->boolean('attempt_via_client');
            $table->string('agency_name');
            $table->float('price_published_agence');
            $table->string('date_of_sale_agence');
            $table->float('price_published_himself');
            $table->string('date_of_sale_himself');
            $table->longText('information_additional');
            $table->boolean('module_visit')->default(0);
            $table->string('date_send_reminder')->default('14:00:00');
            $table->boolean('send_reminder_half_past_eight')->default(0);
            $table->boolean('rdv')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('seller')->references('id')->on('sellers');
            $table->foreign('notary')->references('id')->on('notaries');
            $table->foreign('agent')->references('id')->on('users');
            $table->foreign('category')->references('id')->on('categories');
        });

        DB::table('estates')->insert([
            'reference' => now(),
            'name' => 'Estate Name',
            'type_estate' => 'Type of Estate',
            'category' => 1,
            'main_photo' => 'main_photo.jpg',
            'street' => 'Estate Street',
            'number' => 123,
            'box' => 0,
            'code_postal' => 1000,
            'city' => 'Estate City',
            'seller' => 1,
            'when_want_sell' => 'When the seller wants to sell the estate',
            'want_tenant_after_sell' => 'What the seller wants to do with the estate after selling it',
            'want_buy_wesold' => 'If the seller wants to buy another estate from us after selling this one',
            'agent' => 1,
            'notary' => 1,
            'estimate' => 100000,
            'market' => 120000,
            'type_of_sale' => 'Type of Sale',
            'attempt_via_agency' => true,
            'attempt_via_client' => false,
            'agency_name' => 'Agency Name',
            'price_published_agence' => 110000,
            'date_of_sale_agence' => '2022-01-01',
            'price_published_himself' => 115000,
            'date_of_sale_himself' => '2022-01-02',
            'information_additional' => 'Additional information about the estate',
            'module_visit' => false,
            'date_send_reminder' => '14:00:00',
            'send_reminder_half_past_eight' => false,
            'rdv' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('estates');
    }
}
