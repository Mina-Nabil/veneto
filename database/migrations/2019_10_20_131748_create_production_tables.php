<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('raw_inventory', function (Blueprint $table) {
            $table->double('RINV_PROD_AMNT')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('raw_inventory', function (Blueprint $table) {
            $table->dropColumn('RINV_PROD_AMNT');
        });
    }
}
