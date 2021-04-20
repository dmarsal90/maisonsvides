<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {

		Schema::create('settings', function (Blueprint $table) {
			$table->bigInteger('id')->autoIncrement(); // ID autoincrement
			$table->string('type'); // Type of setting
			$table->string('name'); // Name of setting
			$table->longText('value'); // Value or values of setting NOTE : Always will be a array serialized
			$table->timestamp('created_at')->useCurrent(); // Created at
			$table->timestamp('updated_at')->useCurrent(); // Updated at
		});

		$remindersDefault = array(1, 2, 3);
		$remindersDefault = serialize($remindersDefault);

		DB::table('settings')->insert(
			array(
				'type' => 'reminders',
				'name' => 'Rappels: Demande N° Tel',
				'value' => $remindersDefault,
			)
		);

		DB::table('settings')->insert(
			array(
				'type' => 'reminders',
				'name' => 'Rappels: Prise de RDV',
				'value' => $remindersDefault,
			)
		);

		DB::table('settings')->insert(
			array(
				'type' => 'reminders',
				'name' => 'Rappels: Réponse à l\'offre',
				'value' => $remindersDefault,
			)
		);

		DB::table('settings')->insert(
			array(
				'type' => 'menu',
				'name' => 'Menu déroulant',
				'value' => 1,
			)
		);
		DB::table('settings')->insert(
			array(
				'type' => 'menu',
				'name' => 'Menu individuel',
				'value' => 0,
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
		Schema::dropIfExists('settings');
	}
}
