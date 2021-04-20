<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstateDetailsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {

		Schema::create('estate_details', function (Blueprint $table) {
			$table->bigInteger('id')->autoIncrement(); // ID autoincrement
			$table->bigInteger('estate_id'); // Estate ID
			$table->longText('description')->nulleable(); // Estate description
			$table->longText('comment')->nulleable(); // Free comment of seller
			$table->longText('problems')->nulleable(); // Problems reported by seller
			$table->longText('encode')->nulleable(); // Details
			$table->longText('adapte')->nulleable(); // Details
			$table->string('year_construction'); // Year of construction
			$table->string('year_renovation'); // Year of renovation
			$table->string('coordinate_x'); // Coordinate x
			$table->string('coordinate_y'); // Coordinate y
			$table->string('peb'); // PEB of estate
			$table->double('price_evaluated'); // Price evaluated
			$table->double('price_market'); // Price market
			$table->longText('visit_remarks')->nulleable(); // Visit remarks
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
		Schema::dropIfExists('estate_details');
	}
}
