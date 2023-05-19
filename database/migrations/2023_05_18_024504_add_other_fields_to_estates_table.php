<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOtherFieldsToEstatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estates', function (Blueprint $table) {
            $table->string('peb')->nullable();
            $table->float('coordinate_x')->nullable();
            $table->float('coordinate_y')->nullable();
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
                $table->dropColumn(['peb', 'coordinate_x', 'coordinate_y']);
            });
        });
    }
}
