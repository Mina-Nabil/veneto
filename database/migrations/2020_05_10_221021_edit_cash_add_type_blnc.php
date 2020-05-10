<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditCashAddTypeBlnc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("cash", function (Blueprint $table){
            $table->double('CASH_TRST_BLNC')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("cash", function (Blueprint $table){
            $table->dropColumn('CASH_TRST_BLNC');
        });
    }
}
