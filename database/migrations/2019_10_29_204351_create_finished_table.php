<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinishedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger("SALS_CLNT_ID");
            $table->datetime("SALS_DATE");
            $table->double("SALS_TOTL_PRCE");
            $table->double("SALS_PAID")->default(0);
            $table->string("SALS_CMNT")->nullable();
            //$table->string("SALS_STTS")->default(1);
            $table->foreign("SALS_CLNT_ID")->references("id")->on("clients");
        });

        Schema::create('finished', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('FNSH_BRND_ID');
            $table->unsignedBigInteger('FNSH_MODL_ID');
            $table->double('FNSH_PRCE');
            $table->integer('FNSH_36_AMNT')->default(0);
            $table->integer('FNSH_38_AMNT')->default(0);
            $table->integer('FNSH_40_AMNT')->default(0);
            $table->integer('FNSH_42_AMNT')->default(0);
            $table->integer('FNSH_44_AMNT')->default(0);
            $table->integer('FNSH_46_AMNT')->default(0);
            $table->integer('FNSH_48_AMNT')->default(0);
            $table->integer('FNSH_50_AMNT')->default(0);
            $table->integer('FNSH_52_AMNT')->default(0);
            $table->foreign('FNSH_BRND_ID')->references('id')->on("brands");
            $table->foreign('FNSH_MODL_ID')->references('id')->on("models");
        });

        Schema::create('sales_items', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('SLIT_FNSH_ID');
            $table->unsignedBigInteger('SLIT_SALS_ID');
            $table->double('SLIT_PRCE');
            $table->integer('FNSH_36_SOLD')->default(0);
            $table->integer('FNSH_38_SOLD')->default(0);
            $table->integer('FNSH_40_SOLD')->default(0);
            $table->integer('FNSH_42_SOLD')->default(0);
            $table->integer('FNSH_44_SOLD')->default(0);
            $table->integer('FNSH_46_SOLD')->default(0);
            $table->integer('FNSH_48_SOLD')->default(0);
            $table->integer('FNSH_50_SOLD')->default(0);
            $table->integer('FNSH_52_SOLD')->default(0);
            $table->foreign('SLIT_FNSH_ID')->references('id')->on('finished');
            $table->foreign('SLIT_SALS_ID')->references('id')->on('sales');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_items');
        Schema::dropIfExists('sales');
        Schema::dropIfExists('finished');
    }
}
