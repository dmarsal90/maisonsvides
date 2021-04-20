<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstateLogsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {

		Schema::create('estate_logs', function (Blueprint $table) {
			$table->bigInteger('id')->autoIncrement(); // ID autoincrement
			$table->bigInteger('estate_id'); // Estate ID
			$table->bigInteger('user_id'); // User that change the status
			$table->longText('old_value')->nullable(); // Save old value
			$table->longText('new_value')->nullable(); // Save new value
			$table->string('field'); // Field estate that was update
			$table->timestamp('created_at')->useCurrent(); // Created at
			$table->foreign('estate_id')->references('id')->on('estates'); // Add foreign key of estate
			$table->foreign('user_id')->references('id')->on('users'); // Add foreign key of status
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('estate_logs');
	}
}
