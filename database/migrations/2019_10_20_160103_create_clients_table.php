<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
      Schema::create('clients', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->string('CLNT_NAME');
        $table->string('CLNT_ARBC_NAME')->nullable();
        $table->double('CLNT_BLNC')->default(0);
        $table->timestamps();
    });

    Schema::create('client_trans', function (Blueprint $table) {

        $table->bigIncrements('id');
        $table->unsignedBigInteger('CLTR_CLNT_ID');
        $table->double("CLTR_BLNC");
        $table->double("CLTR_SALS_AMNT")->default(0);
        $table->double("CLTR_SALS_BLNC");
        $table->double("CLTR_CASH_AMNT")->default(0);
        $table->double("CLTR_CASH_BLNC");
        $table->double("CLTR_NTPY_AMNT")->default(0);
        $table->double("CLTR_NTPY_BLNC");
        $table->double("CLTR_DISC_AMNT")->default(0);
        $table->double("CLTR_DISC_BLNC");
        $table->double("CLTR_RTRN_AMNT")->default(0);
        $table->double("CLTR_RTRN_BLNC");
        $table->dateTime("CLTR_DATE");
        $table->string("CLTR_CMNT")->nullable();
        $table->foreign("CLTR_CLNT_ID")->references("id")->on("clients");

    });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_trans');
        Schema::dropIfExists('clients');
    }
}
