<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateCategoriesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {

		Schema::create('categories', function (Blueprint $table) {
			$table->bigInteger('id')->autoIncrement(); // ID autoincrement
			$table->string('name'); // Name of category
			$table->string('slug')->unique(); // Slug of categry
			$table->bigInteger('parent')->nullable(); // Category parent
			$table->boolean('has_child')->default(0); // If the category has Child 1 => Yes, 0 => No, default is 0
			$table->bigInteger('count')->default(0); // Count of number dossier of category default is 0
			$table->timestamp('created_at')->useCurrent(); // Created at
			$table->timestamp('updated_at')->useCurrent(); // Updated at
			$table->timestamp('deleted_at')->nullable(); // Deleted at
		});

		DB::table('categories')->insert( // Create category
			array(
				'name' => 'Général',
				'slug' => 'general',
				'parent' => 0,
			)
		);
		DB::table('categories')->insert( // Create category
			array(
				'name' => 'Nouvelles demandes',
				'slug' => 'nouvelles-demandes',
				'parent' => 0,
			)
		);
		DB::table('categories')->insert( // Create category
			array(
				'name' => 'En attente de prise de rendez-vous',
				'slug' => 'en-attente-de-prise-de-rendez-vous',
				'parent' => 0,
			)
		);
		DB::table('categories')->insert( // Create category
			array(
				'name' => 'En attente d’une offre.',
				'slug' => 'en-attente-d-une-offre',
				'parent' => 0,
			)
		);
		DB::table('categories')->insert( // Create category
			array(
				'name' => 'En attente d’information client',
				'slug' => 'en-attente-d-information-client',
				'parent' => 0,
			)
		);
		DB::table('categories')->insert( // Create category
			array(
				'name' => 'Rendez-vous planifié(s)',
				'slug' => 'rendez-vous-planifies',
				'parent' => 0,
			)
		);
		DB::table('categories')->insert( // Create category
			array(
				'name' => 'Offres acceptées',
				'slug' => 'offres-acceptees',
				'parent' => 0,
			)
		);
		DB::table('categories')->insert( // Create category
			array(
				'name' => 'Offre(s) en attente d’être envoyée(s)',
				'slug' => 'offres-en-attente-d-etre-envoyees',
				'parent' => 0,
			)
		);
		DB::table('categories')->insert( // Create category
			array(
				'name' => 'Clôturés',
				'slug' => 'clotures',
				'parent' => 5,
			)
		);
		DB::table('categories')->insert( // Create category
			array(
				'name' => 'Annulés',
				'slug' => 'annules',
				'parent' => 1,
			)
		);
		DB::table('categories')->insert( // Create category
			array(
				'name' => 'Archivés',
				'slug' => 'archives',
				'parent' => 1,
			)
		);
		DB::table('categories')->insert( // Create category
			array(
				'name' => 'N° Tel',
				'slug' => 'n-tel',
				'parent' => 5,
			)
		);
		DB::table('categories')->insert( // Create category
			array(
				'name' => 'RDV pris',
				'slug' => 'rdv-pris',
				'parent' => 5,
			)
		);
		DB::table('categories')->insert( // Create category
			array(
				'name' => 'Visite fini',
				'slug' => 'visite-fini',
				'parent' => 0,
			)
		);
		DB::table('categories')->insert( // Create category
			array(
				'name' => 'Rép. OFFRE',
				'slug' => 'rep-offre',
				'parent' => 5,
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
		Schema::dropIfExists('categories');
	}
}
