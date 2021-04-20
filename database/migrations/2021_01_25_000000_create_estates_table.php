<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstatesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {

		Schema::create('estates', function (Blueprint $table) {
			$table->bigInteger('id')->autoIncrement(); // ID autoincrement
			$table->timestamp('reference')->useCurrent(); // Name of estate
			$table->string('name'); // Name of estate
			$table->string('type_estate'); // Type of estate
			$table->bigInteger('category'); // Category ID
			$table->string('visit_date_at')->nullable(); // Date of visit
			$table->string('main_photo'); // Main photo to show in the view
			$table->string('street'); // Street of estate
			$table->integer('number'); // Number of estate
			$table->integer('box'); // Box = Boite of estate
			$table->integer('code_postal'); // Code postal of estate
			$table->string('city'); // City of estate
			$table->bigInteger('seller'); // Seller ID
			$table->longText('when_want_sell'); // When want to sell
			$table->longText('want_tenant_after_sell'); // Want to remain a tenant after sell
			$table->longText('want_buy_wesold'); // Want to by Wesold
			$table->bigInteger('agent'); // Agent ID
			$table->bigInteger('notary'); // Notary ID
			$table->double('estimate'); // Estimate
			$table->double('market'); // Market
			$table->string('type_of_sale'); // Type of sale of the estate
			$table->boolean('attempt_via_agency'); // Attempt via agency 1 => Yes, 0 => No
			$table->boolean('attempt_via_client'); // Attempt via agency 1 => Yes, 0 => No
			$table->string('agency_name'); // Agency name
			$table->float('price_published_agence'); // Price published
			$table->string('date_of_sale_agence'); // Start date of sale
			$table->float('price_published_himself'); // Price published
			$table->string('date_of_sale_himself'); // Start date of sale
			$table->longText('information_additional'); // Information additional
			$table->boolean('module_visit')->default(0); // Module visit 1 => Yes, 0 => No
			$table->string('date_send_reminder')->default('14:00:00'); // Time to send the cron
			$table->boolean('send_reminder_half_past_eight')->default(0); // Send reminder 8:30 1 => Yes, 0 => No
			$table->boolean('rdv')->default(0); // Module visit 1 => Yes, 0 => No
			$table->timestamp('created_at')->useCurrent(); // Created at
			$table->timestamp('updated_at')->useCurrent(); // Updated at
			$table->timestamp('deleted_at')->nullable(); // Deleted at
			$table->foreign('seller')->references('id')->on('sellers'); // Add foreign key of seller type
			$table->foreign('notary')->references('id')->on('notaries'); // Add foreign key of seller type
			$table->foreign('agent')->references('id')->on('users'); // Add foreign key of agent type
			$table->foreign('category')->references('id')->on('categories'); // Add foreign key of category type
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('estates');
	}
}
