<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotariesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('notaries', function (Blueprint $table) {
			$table->bigInteger('id')->autoIncrement(); // ID autoincrement
			$table->string('title'); // Title of the notary
			$table->string('name'); // Name of notary
			$table->string('lastname'); // Lastname of notary
			$table->string('address'); // Address of notary
			$table->string('phone'); // Phone of notary
			$table->string('email')->unique(); // Email of notary
			$table->string('key'); // Key of notary
			$table->timestamp('created_at')->useCurrent(); // Created at
			$table->timestamp('updated_at')->useCurrent(); // Updated at
			$table->timestamp('deleted_at')->nullable(); // Deleted at
		});

		DB::table('notaries')->insert( // Create the first register to access the system
			array(
				'title' => 'Maitre',
				'name' => 'No notary',
				'lastname' => 'nonotary',
				'address' => 'Adresse',
				'phone' => '123456789',
				'email' => 'nonotary.flexvision@gmail.com',
				'key' => '000.000'
			),
		);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('notaries');
	}
}
