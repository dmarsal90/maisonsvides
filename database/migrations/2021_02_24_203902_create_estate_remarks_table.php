<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstateRemarksTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('estate_remarks', function (Blueprint $table) {
			$table->bigInteger('id')->autoIncrement(); // ID autoincrement
			$table->unsignedBigInteger('estate_id'); // Estate ID
			$table->longText('interior_state'); // State interior of the maison
			$table->longText('exterior_state'); // State exterior of the maison
			$table->longText('district_state'); // State of the neighborhood
			$table->longText('interior_highlights'); // Interior highlights
			$table->longText('exterior_highlights'); // Exterior highlights
			$table->longText('interior_weak_point'); // Interior weaks points
			$table->longText('exterior_weak_point'); // Exterior weaks points
			$table->bigInteger('desires_to_sell'); // Owner's wishes to sell by percentage
			$table->double('his_estimate'); // Owner's estimate
			$table->string('accept_price'); // Accept owner price
			$table->longText('agent_notice'); // Agent opinion
			$table->timestamp('created_at')->useCurrent(); // Created at
			$table->timestamp('updated_at')->useCurrent(); // Updated at
			$table->timestamp('deleted_at')->nullable(); // Deleted at
			$table->foreign('estate_id')->references('id')->on('estates'); // Add foreign key of estate
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('estate_remarks');
	}
}
