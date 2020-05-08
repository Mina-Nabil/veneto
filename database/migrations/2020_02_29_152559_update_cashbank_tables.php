<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCashbankTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table("cash", function(Blueprint $table){
            $table->unsignedInteger("CASH_TRST_ID")->nullable();
            $table->foreign("CASH_TRST_ID")->references("id")->on("trans_subtype");
        });
        Schema::table("bank", function(Blueprint $table){
            $table->unsignedInteger("BANK_TRST_ID")->nullable();
            $table->foreign("BANK_TRST_ID")->references("id")->on("trans_subtype");
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
        Schema::table('cash', function(Blueprint $table){
            $table->dropForeign('cash_cash_trst_id_foreign');
            $table->dropColumn('CASH_TRST_ID');
        });
        Schema::table('bank', function(Blueprint $table){
            $table->dropForeign('bank_bank_trst_id_foreign');
            $table->dropColumn('BANK_TRST_ID');
        });
    }
}
