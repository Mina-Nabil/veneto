<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

      Schema::create('supplier_types', function(Blueprint $table){
        $table->increments('id');
        $table->string('SPTP_NAME');
      });

      Schema::create('suppliers', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->string('SUPP_NAME');
          $table->string('SUPP_ARBC_NAME')->nullable();
          $table->double('SUPP_BLNC')->default(0);
          $table->unsignedInteger('SUPP_SPTP_ID');
          $table->foreign('SUPP_SPTP_ID')->references('id')->on('supplier_types');
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
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('supplier_types');
    }
}
