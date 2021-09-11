<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateModellat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("models", function (Blueprint $table) {
            $table->decimal("MODL_PRCE")->nullable()->change();
            $table->unsignedBigInteger("MODL_SUPP_ID")->nullable()->change();
            $table->unsignedInteger('MODL_TYPS_ID')->nullable()->change();
            $table->unsignedInteger('MODL_COLR_ID')->nullable()->change();
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
