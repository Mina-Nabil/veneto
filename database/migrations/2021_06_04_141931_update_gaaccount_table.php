<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateGaaccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("gen_accounts_trans", function(Blueprint $table){
            $table->integer("GNTR_EROR")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("gen_accounts_trans", function(Blueprint $table){
            $table->dropColumn("GNTR_EROR");
        });
    }
}
