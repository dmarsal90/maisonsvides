<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstateReminderTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('estate_reminder', function (Blueprint $table) {
			$table->bigInteger('id')->autoIncrement(); // ID autoincrement
			$table->unsignedBigInteger('estate_id'); // Estate ID
			$table->bigInteger('user_id'); // User id
			$table->string('name_reminder'); // Name of reminder
			$table->longText('content'); // Date of reminder
			$table->boolean('sent'); // Sent 1 => Yes, 0 => No
			$table->boolean('hide'); // Hide 1 => Yes, 0 => No
			$table->bigInteger('next_reminder'); // Number of next reminder to send
			$table->timestamp('created_at')->useCurrent(); // Created at
			$table->timestamp('updated_at')->useCurrent(); // Updated at
			$table->timestamp('deleted_at')->nullable(); // Deleted at
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
		Schema::dropIfExists('estate_reminder');
	}
}
