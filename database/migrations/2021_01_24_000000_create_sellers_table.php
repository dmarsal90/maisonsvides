<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSellersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sellers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->bigInteger('phone');
            $table->string('type');
            $table->string('contact_by');
            $table->string('reason_sale');
            $table->string('looking_property');
            $table->boolean('want_stay_tenant');
            $table->string('when_to_buy');
            $table->timestamps();
            $table->softDeletes();
        });

        // Insert 2 sellers
        DB::table('sellers')->insert([
            [
                'name' => 'Seller 1',
                'email' => 'seller1@example.com',
                'phone' => '123456789',
                'type' => 'Individual',
                'contact_by' => 'Email',
                'reason_sale' => 'Moving to another city',
                'looking_property' => 'Yes',
                'want_stay_tenant' => false,
                'when_to_buy' => 'Within the next year',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Seller 2',
                'email' => 'seller2@example.com',
                'phone' => '987654321',
                'type' => 'Company',
                'contact_by' => 'Phone',
                'reason_sale' => 'Downsizing',
                'looking_property' => 'No',
                'want_stay_tenant' => true,
                'when_to_buy' => 'Not sure yet',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sellers');
    }
}
