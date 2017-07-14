<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSafeZonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('safe_zones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('start_location')->unsigned();
            $table->integer('end_location')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->foreign('start_location')->references('id')->on('locations')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('end_location')->references('id')->on('locations')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });

        Schema::table('games', function($table) {
            $table->foreign('starting_point')->references('id')->on('locations')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('safe_zones');
    }
}
