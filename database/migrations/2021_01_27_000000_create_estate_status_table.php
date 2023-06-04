<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstateStatusTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {

		Schema::create('estate_status', function (Blueprint $table) {
			$table->bigInteger('id')->autoIncrement(); // ID autoincrement
			$table->unsignedBigInteger('estate_id'); // Estate ID
			$table->bigInteger('category_id'); // Estate Status ID
			$table->bigInteger('user_id'); // User that change the status
			$table->timestamp('start_at')->nullable(); // Created at
			$table->timestamp('stop_at')->nullable(); // Updated at
			$table->foreign('estate_id')->references('id')->on('estates'); // Add foreign key of estate
			$table->foreign('category_id')->references('id')->on('categories'); // Add foreign key of status
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
		Schema::dropIfExists('estate_status');
	}
}
