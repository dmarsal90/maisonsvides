<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTypesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {

		Schema::create('user_types', function (Blueprint $table) {
			$table->integer('id')->autoIncrement(); // ID autoincrement
			$table->string('name'); // Name of user type
			$table->timestamp('created_at')->useCurrent(); // Created at
			$table->timestamp('updated_at')->useCurrent(); // Updated at
			$table->timestamp('deleted_at')->nullable(); // Deleted at
		});

		DB::table('user_types')->insert( // Create the user Administrateur
			array(
				'name' => 'Administrateur',
			)
		);

		DB::table('user_types')->insert( // Create the user Secretariat
			array(
				'name' => 'Gestionnaire de zone',
			)
		);

		DB::table('user_types')->insert( // Create the user Agent
			array(
				'name' => 'Agent',
			)
		);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('user_types');
	}
}
