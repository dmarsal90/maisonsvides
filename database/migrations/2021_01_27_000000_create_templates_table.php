<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemplatesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {

		Schema::create('templates', function (Blueprint $table) {
			$table->bigInteger('id')->autoIncrement(); // ID autoincrement
			$table->string('name'); // Name of template
			$table->string('subject'); // Subject of template
			$table->string('file'); // Name of file
			$table->string('type'); // Type of template Email, SMS
			$table->timestamp('created_at')->useCurrent(); // Created at
			$table->timestamp('updated_at')->useCurrent(); // Updated at
		});

		DB::table('templates')->insert( // Create the first register to access the system
			array(
				'name' => 'template-subject',
				'subject' => '',
				'file' => 'Wesold vous propose une offre d\'achat pour votre bien',
				'type' => 'subject',
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
		Schema::dropIfExists('templates');
	}
}
