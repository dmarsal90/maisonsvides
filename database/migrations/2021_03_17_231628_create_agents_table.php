<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('agents', function (Blueprint $table) {
			$table->bigInteger('id')->autoIncrement(); // ID autoincrement
			$table->bigInteger('agent_id'); // Name of user
			$table->bigInteger('maneger_id'); // Email user unique
			$table->timestamp('created_at')->useCurrent(); // Created at
			$table->timestamp('updated_at')->useCurrent(); // Updated at
			$table->timestamp('deleted_at')->nullable(); // Deleted at
			$table->foreign('agent_id')->references('id')->on('users'); // Add foreign key of user type
			$table->foreign('maneger_id')->references('id')->on('users'); // Add foreign key of user type
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('agents');
	}
}
