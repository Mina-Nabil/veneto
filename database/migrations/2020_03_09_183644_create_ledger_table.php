<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLedgerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ledger', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('LDGR_DATE');
            $table->string('LDGR_NAME');
            $table->double('LDGR_IN')->default(0);
            $table->double('LDGR_OUT')->default(0);
            $table->double('LDGR_BLNC');
            $table->unsignedInteger("LDGR_TRST_ID")->nullable();
            $table->integer("LDGR_EROR")->default(0);
            $table->foreign("LDGR_TRST_ID")->references("id")->on("trans_subtype");
            $table->text('LDGR_CMNT')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ledger');
    }
}
