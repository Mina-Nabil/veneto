<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRawTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raw', function (Blueprint $table) {
            $table->increments('id');
            $table->string("RAW_NAME");
        });

        Schema::create('colors', function(Blueprint $table){
            $table->increments('id');
            $table->string('COLR_NAME');
            $table->string('COLR_CODE');
        });

        Schema::create('types', function (Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('TYPS_RAW_ID');
            $table->string('TYPS_NAME');
            $table->foreign('TYPS_RAW_ID')->references('id')->on('raw');
        });

        Schema::create('models', function (Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedInteger('MODL_TYPS_ID');
            $table->unsignedInteger('MODL_COLR_ID');
            $table->string('MODL_NAME')->nullable();
            $table->string('MODL_UNID')->nullable();
            $table->string('MODL_IMGE')->nullable();
            $table->string('MODL_CMNT')->nullable();
            $table->foreign('MODL_TYPS_ID')->references('id')->on('types');
            $table->foreign('MODL_COLR_ID')->references('id')->on('colors');
        });

        Schema::create('raw_inventory', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('RINV_MODL_ID');
            $table->unsignedBigInteger('RINV_SUPP_ID');
            $table->dateTime('RINV_ENTR_DATE');
            $table->integer('RINV_STAT')->default(1);
            $table->double('RINV_METR');
            $table->double('RINV_PRCE');
            $table->foreign("RINV_MODL_ID")->references('id')->on('models');
            $table->foreign("RINV_SUPP_ID")->references('id')->on('suppliers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {   
        Schema::dropIfExists('raw_inventory');
        Schema::dropIfExists('models');
        Schema::dropIfExists('colors');
        Schema::dropIfExists('types');
        Schema::dropIfExists('raw');
    }
}
