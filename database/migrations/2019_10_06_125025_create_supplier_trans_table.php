<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierTransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_trans', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->unsignedBigInteger('SPTR_SUPP_ID');
            $table->double("SPTR_BLNC");
            $table->double("SPTR_PRCH_AMNT")->default(0);
            $table->double("SPTR_PRCH_BLNC");
            $table->double("SPTR_CASH_AMNT")->default(0);
            $table->double("SPTR_CASH_BLNC");
            $table->double("SPTR_NTPY_AMNT")->default(0);
            $table->double("SPTR_NTPY_BLNC");
            $table->double("SPTR_DISC_AMNT")->default(0);
            $table->double("SPTR_DISC_BLNC");
            $table->double("SPTR_RTRN_AMNT")->default(0);
            $table->double("SPTR_RTRN_BLNC");
            $table->dateTime("SPTR_DATE");
            $table->string("SPTR_CMNT")->nullable();
            $table->foreign("SPTR_SUPP_ID")->references("id")->on("suppliers");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplier_trans');
    }
}
