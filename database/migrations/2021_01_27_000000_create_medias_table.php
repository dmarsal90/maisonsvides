<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediasTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {

		Schema::create('medias', function (Blueprint $table) {
			$table->bigInteger('id')->autoIncrement(); // ID autoincrement
			$table->bigInteger('estate_id'); // Estate ID
			$table->string('name'); // Name of media
			$table->string('file_name'); // Name of file
			$table->string('type'); // Type of media photo, document
			$table->double('size'); // Size of file
			$table->string('extension'); // Extension of file
			$table->timestamp('created_at')->useCurrent(); // Created at
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
		Schema::dropIfExists('medias');
	}
}
