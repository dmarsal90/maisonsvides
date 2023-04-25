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

        DB::table('estates')->insert([
            'reference' => now(),
               'name' => 'Estate 1',
               'type_estate' => 'Type of Estate 1',
               'category' => 1,
               'main_photo' => 'main_photo1.jpg',
               'street' => 'Estate Street 1',
               'number' => 123,
               'box' => 0,
               'code_postal' => 1000,
               'city' => 'Estate City 1',
               'seller' => 1,
               'when_want_sell' => 'When the seller wants to sell the estate 1',
               'want_tenant_after_sell' => 'What the seller wants to do with the estate after selling it 1',
               'want_buy_wesold' => 'If the seller wants to buy another estate from us after selling this one 1',
               'agent' => 1,
               'notary' => 1,
               'estimate' => 100000,
               'market' => 120000,
               'type_of_sale' => 'Type of Sale 1',
               'attempt_via_agency' => true,
               'attempt_via_client' => false,
               'agency_name' => 'Agency Name 1',
               'price_published_agence' => 110000,
               'date_of_sale_agence' => '2022-01-01',
               'price_published_himself' => 115000,
               'date_of_sale_himself' => '2022-01-02',
               'information_additional' => 'Additional information about the estate 1',
               'module_visit' => false,
               'date_send_reminder' => '14:00:00',
               'send_reminder_half_past_eight' => false,
               'rdv' => false,
               'created_at' => now(),
               'updated_at' => now()
        ]);

        DB::table('estates')->insert([
            'reference' => now(),
               'name' => 'Estate 2',
               'type_estate' => 'Type of Estate 2',
               'category' => 2,
               'main_photo' => 'main_photo2.jpg',
               'street' => 'Estate Street 2',
               'number' => 456,
               'box' => 0,
               'code_postal' => 2000,
               'city' => 'Estate City 2',
               'seller' => 2,
               'when_want_sell' => 'When the seller wants to sell the estate 2',
               'want_tenant_after_sell' => 'What the seller wants to do with the estate after selling it 2',
               'want_buy_wesold' => 'If the seller wants to buy another estate from us after selling this one 2',
               'agent' => 1,
               'notary' => 1,
               'estimate' => 200000,
               'market' => 220000,
               'type_of_sale' => 'Type of Sale 2',
               'attempt_via_agency' => true,
               'attempt_via_client' => false,
               'agency_name' => 'Agency Name 2',
               'price_published_agence' => 210000,
               'date_of_sale_agence' => '2022-02-01',
               'price_published_himself' => 215000,
               'date_of_sale_himself' => '2022-02-02',
               'information_additional' => 'Additional information about the estate 2',
               'module_visit' => false,
               'date_send_reminder' => '14:00:00',
               'send_reminder_half_past_eight' => false,
               'rdv' => false,
               'created_at' => now(),
               'updated_at' => now()
        ]);

        DB::table('estates')->insert([
            'reference' => now(),
               'name' => 'Estate 3',
               'type_estate' => 'Type of Estate 3',
               'category' => 3,
               'main_photo' => 'main_photo3.jpg',
               'street' => 'Estate Street 3',
               'number' => 789,
               'box' => 0,
               'code_postal' => 3000,
               'city' => 'Estate City 3',
               'seller' => 2,
               'when_want_sell' => 'When the seller wants to sell the estate 3',
               'want_tenant_after_sell' => 'What the seller wants to do with the estate after selling it 3',
               'want_buy_wesold' => 'If the seller wants to buy another estate from us after selling this one 3',
               'agent' => 1,
               'notary' => 1,
               'estimate' => 300000,
               'market' => 320000,
               'type_of_sale' => 'Type of Sale 3',
               'attempt_via_agency' => true,
               'attempt_via_client' => false,
               'agency_name' => 'Agency Name 3',
               'price_published_agence' => 310000,
               'date_of_sale_agence' => '2022-03-01',
               'price_published_himself' => 315000,
               'date_of_sale_himself' => '2022-03-02',
               'information_additional' => 'Additional information about the estate 3',
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
