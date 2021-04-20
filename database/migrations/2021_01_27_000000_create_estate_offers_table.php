<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstateOffersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {

		Schema::create('estate_offers', function (Blueprint $table) {
			$table->bigInteger('id')->autoIncrement(); // ID autoincrement
			$table->bigInteger('estate_id'); // Estate ID
			$table->double('price_seller'); // Price estimated by the seller
			$table->double('price_wesold'); // Price estimated by Wesold
			$table->double('price_market'); // Price estimated by market
			$table->longText('other_offer'); // Text to add another offer
			$table->string('notaire'); // Data of the notary
			$table->string('condition'); // Data of the condition of the offer
			$table->string('validity'); // Date of de validity of the offer
			$table->longText('textadded'); // Text added to pdf
			$table->string('pdf'); // Route of PDF to send in email
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
		Schema::dropIfExists('estate_offers');
	}
}
