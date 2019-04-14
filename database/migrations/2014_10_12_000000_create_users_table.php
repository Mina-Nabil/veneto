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

        Schema::dropIfExists('user_types');
        Schema::create('user_types', function(Blueprint $table){
            $table->increments('id');
            $table->string('name');

        });

        Schema::dropIfExists('users');

        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username')->unique();
            $table->string('fullname');
            $table->string('image')->nullable();
            $table->string('password');
            $table->string('mobNumber');
            $table->unsignedInteger('typeID');
            $table->foreign('typeID')->references('id')
                    ->on('user_types');
            $table->rememberToken();
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
        Schema::dropIfExists('user_types');
    }
}
