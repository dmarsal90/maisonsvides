<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {

		Schema::create('sellers', function (Blueprint $table) {
			$table->bigInteger('id')->autoIncrement(); // ID autoincrement
			$table->string('name'); // Name of Contact
			$table->string('email')->unique(); // Email of contact
			$table->bigInteger('phone'); // Phone of contact
			$table->string('type'); // Type of contact free text
			$table->string('contact_by'); // Method of contact
			$table->string('reason_sale'); // Reason for sale
			$table->string('looking_property'); // Looking for another property
			$table->boolean('want_stay_tenant'); // Want to stay a tenant 1 => Yes, 0 => No
			$table->string('when_to_buy'); // When to buy
			$table->timestamp('created_at')->useCurrent(); // Created at
			$table->timestamp('updated_at')->useCurrent(); // Updated at
			$table->timestamp('deleted_at')->nullable(); // Deleted at
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('sellers');
	}
}
