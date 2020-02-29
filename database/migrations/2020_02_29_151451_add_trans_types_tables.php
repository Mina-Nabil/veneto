<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTransTypesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create("trans_type", function(Blueprint $table){
            $table->increments('id');
            $table->string('TRTP_NAME');

        });

        Schema::create("trans_subtype", function(Blueprint $table){
            $table->increments('id');
            $table->string('TRST_NAME');
            $table->unsignedInteger('TRST_TRTP_ID');
            $table->foreign('TRST_TRTP_ID')->references('id')->on('trans_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('trans_subtype');
        Schema::dropIfExists('trans_type');
    }
}
