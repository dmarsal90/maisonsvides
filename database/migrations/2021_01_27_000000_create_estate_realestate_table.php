<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstateRealestateTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {

		Schema::create('estate_realestate', function (Blueprint $table) {
			$table->bigInteger('id')->autoIncrement(); // ID autoincrement
			$table->bigInteger('estate_id'); // Estate ID
			$table->bigInteger('realestate_id'); // Realestate ID
			$table->string('refrence'); // Reference Realestate
			$table->string('url'); // URL Realestate
			$table->date('put_online'); // Date of put online realestate
			$table->double('price'); // Price realestate
			$table->timestamp('created_at')->useCurrent(); // Created at
			$table->timestamp('updated_at')->useCurrent(); // Updated at
			$table->timestamp('deleted_at')->nullable(); // Deleted at
			$table->foreign('estate_id')->references('id')->on('estates'); // Add foreign key of estate
			$table->foreign('realestate_id')->references('id')->on('realestates'); // Add foreign key of estate
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('estate_realestate');
	}
}
