<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLedgerTypesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create("ledger_type", function(Blueprint $table){
            $table->increments('id');
            $table->string('LDTP_NAME');

        });

        Schema::create("ledger_subtype", function(Blueprint $table){
            $table->increments('id');
            $table->string('LDST_NAME');
            $table->unsignedInteger('LDST_LDTP_ID');
            $table->foreign('LDST_LDTP_ID')->references('id')->on('ledger_type');
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
        Schema::dropIfExists("ledger_subtype");
        Schema::dropIfExists("ledger_type");
    }
}
