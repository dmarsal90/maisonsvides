<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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
            $table->bigIncrements('id');
            $table->bigInteger('agent_id');
            $table->bigInteger('manager_id');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('agent_id')->references('id')->on('users');
            $table->foreign('manager_id')->references('id')->on('users');
        });

        // Insert 3 agents
        DB::table('agents')->insert([
            [
                'agent_id' => 1,
                'manager_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'agent_id' => 3,
                'manager_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'agent_id' => 2,
                'manager_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
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
