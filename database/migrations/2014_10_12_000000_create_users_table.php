<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->date('date_of_birth')->nullable();
            $table->string('api_token', 60)->unique();
            $table->string('avatar')->default('default.jpg');
            $table->integer('current_location')->unsigned()->nullable();
            $table->integer('current_game')->unsigned()->nullable();
            $table->integer('safe_zone')->unsigned()->nullable();
            $table->boolean('in_safe_zone')->default(false);
            $table->boolean('hunter')->default(false);
            $table->integer('points')->default(0);
            $table->integer('timer')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
