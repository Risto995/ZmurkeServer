<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('number_of_players');
            $table->time('time_limit');
            $table->integer('starting_point')->unsigned();
            $table->integer('created_by')->unsigned();
            $table->integer('winner')->unsigned()->nullable();
            $table->timestamps();
        });

        Schema::table('users', function($table) {
            $table->foreign('current_location')->references('id')->on('locations')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('current_game')->references('id')->on('games')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
    }
}
