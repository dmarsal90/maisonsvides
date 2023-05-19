<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToEstatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estates', function (Blueprint $table) {

            $table->integer('surface')->nullable();
            $table->boolean('garden')->nullable();
            $table->boolean('terrase')->nullable();
            $table->boolean('garage')->nullable();
            $table->string('town_planning')->nullable();
            $table->boolean('more_habitations')->nullable();
            $table->integer('number_bathroom')->nullable();
            $table->integer('number_rooms')->nullable();
            $table->integer('number_gas')->nullable();
            $table->integer('number_electric')->nullable();
            $table->integer('state_interior')->nullable();
            $table->integer('state_exterior')->nullable();
            $table->year('construction')->nullable();
            $table->year('renovation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('estates', function (Blueprint $table) {
            Schema::table('estates', function (Blueprint $table) {
                $table->dropColumn(['type_estate', 'surface', 'garden', 'terrase', 'garage', 'town_planning', 'more_habitations', 'number_bathroom', 'number_rooms', 'number_gas', 'number_electric', 'state_interior', 'state_exterior']);
            });
        });
    }
}
