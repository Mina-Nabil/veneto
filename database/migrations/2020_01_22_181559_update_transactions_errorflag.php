<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTransactionsErrorflag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table("bank", function(Blueprint $table){
            $table->integer("BANK_EROR")->default(0);
        });
        Schema::table("cash", function(Blueprint $table){
            $table->integer("CASH_EROR")->default(0);
        });
        Schema::table("client_trans", function(Blueprint $table){
            $table->integer("CLTR_EROR")->default(0);
        });
        Schema::table("supplier_trans", function(Blueprint $table){
            $table->integer("SPTR_EROR")->default(0);
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
        Schema::table("bank", function(Blueprint $table){
            $table->dropColumn("BANK_EROR");
        });
        Schema::table("cash", function(Blueprint $table){
            $table->dropColumn("CASH_EROR");
        });
        Schema::table("client_trans", function(Blueprint $table){
            $table->dropColumn("CLTR_EROR");
        });
        Schema::table("supplier_trans", function(Blueprint $table){
            $table->dropColumn("SPTR_EROR");
        });
    }
}
