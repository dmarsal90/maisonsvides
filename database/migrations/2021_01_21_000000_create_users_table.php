<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {

		Schema::create('users', function (Blueprint $table) {
			$table->bigInteger('id')->autoIncrement(); // ID autoincrement
			$table->string('name'); // Name of user
			$table->string('email')->unique(); // Email user unique
			$table->string('login')->unique(); // Username
			$table->timestamp('email_verified_at')->nullable(); // Email verified at
			$table->string('password'); // Password bcrypt
			$table->integer('type'); // Type of contact
			$table->rememberToken(); // Remember token
			$table->string('google_email')->unique(); // Email of google
			$table->longText('google_token')->nullable(); // Token of google
			$table->boolean('active'); // Status 1 => active, 0 => inactive
			$table->string('menu')->default('Menu individuel'); // Type of menu -> Menu déroulant or Menu individuel
			$table->timestamp('created_at')->useCurrent(); // Created at
			$table->timestamp('updated_at')->useCurrent(); // Updated at
			$table->timestamp('deleted_at')->nullable(); // Deleted at
			$table->foreign('type')->references('id')->on('user_types'); // Add foreign key of user type
		});

		DB::table('users')->insert( // Create the first register to access the system
			array(
				'name' => 'No agent',
				'email' => 'noagent@flexvision.be',
				'login' => 'noagent',
				'password' => Hash::make('1234567890'),
				'type' => 3,
				'google_email' => 'noagent.flexvision@gmail.com',
				'active' => 1
			),
		);

		DB::table('users')->insert( // Create the first register to access the system
			array(
				'name' => 'Administrateur Administrateur',
				'email' => 'cesar@flexvision.be',
				'login' => 'sadmin',
				'password' => Hash::make('1234567890'),
				'type' => 1,
				'google_email' => 'cesar.flexvision@gmail.com',
				'active' => 1
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
		Schema::dropIfExists('users');
	}
}
