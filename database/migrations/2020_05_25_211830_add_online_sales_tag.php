<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOnlineSalesTag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("sales", function (Blueprint $table) {
            $table->tinyInteger("SALS_ONLN")->default(0);
        });

        Schema::table("clients", function (Blueprint $table) {
            $table->tinyInteger("CLNT_ONLN")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("sales", function (Blueprint $table) {
            $table->dropIfExists("SALS_ONLN");
        });
        Schema::table("clients", function (Blueprint $table) {
            $table->dropIfExists("CLNT_ONLN");
        });
    }
}
