<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('models', function(Blueprint $table){
            $table->double("MODL_PRCE");
            $table->unsignedBigInteger("MODL_SUPP_ID");
            $table->foreign("MODL_SUPP_ID")->references("id")->on("suppliers");
        });

        Schema::table('raw_inventory', function (Blueprint $table){
            $table->dropForeign("raw_inventory_rinv_supp_id_foreign");
            $table->bigInteger("RINV_TRNS");
            $table->dropColumn("RINV_SUPP_ID");
            $table->dropColumn("RINV_PRCE");
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
