<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstatesEventsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {

		Schema::create('estates_events', function (Blueprint $table) {
			$table->bigInteger('id')->autoIncrement(); // ID Autoincrement
			$table->bigInteger('estate_id'); // Estate ID
			$table->string('event_id'); // Event ID
			$table->bigInteger('user_id'); // User ID
			$table->bigInteger('seller_id'); // Seller ID
			$table->boolean('confirmed')->default(0); // Confirm 1 => Yes, 0 => No
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('estates_events');
	}
}
