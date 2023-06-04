<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstateCommentsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {

		Schema::create('estate_comments', function (Blueprint $table) {
			$table->bigInteger('id')->autoIncrement(); // ID autoincrement
			$table->unsignedBigInteger('estate_id'); // Estate ID
			$table->bigInteger('user_id'); // User that comment
			$table->longText('comment')->nullable(); // User comment
			$table->timestamp('created_at')->useCurrent(); // Created at
			$table->timestamp('updated_at')->useCurrent(); // Updated at
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
		Schema::dropIfExists('estate_comments');
	}
}
