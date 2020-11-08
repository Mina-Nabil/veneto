<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHideTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_trans', function (Blueprint $table) {
            $table->tinyInteger('CLTR_HDDN')->default(0);
        });
        Schema::table('supplier_trans', function (Blueprint $table) {
            $table->tinyInteger('SPTR_HDDN')->default(0);

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
    }
}
