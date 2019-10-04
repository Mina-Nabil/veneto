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
      Schema::create('supplier_type', function(Blueprint $table){
        $table->smallIncrements('id');
        $table->string('SPTP_NAME');
      });

      Schema::create('suppliers', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->string('SUPP_NAME');
          $table->string('SUPP_ADRS')->nullable();
          $table->double('SUPP_BLNC')->nullable();
          $table->timestamps();
      });

      Schema::create('supplier_to_type', function (Blueprint $table){
        $table->mediumIncrements('id');
        $table->bigInteger('STOP_SUPP_ID')->fo
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
    }
}
