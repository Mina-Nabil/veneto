<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('clients', function (Blueprint $table){
            $table->string("CLNT_ADRS")->nullable();
            $table->string("CLNT_TELE")->nullable();
            $table->string("CLNT_CMNT")->nullable();
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
        Schema::table('clients', function(Blueprint $table){
            $table->dropColumn("CLNT_ADRS");
            $table->dropColumn("CLNT_TELE");
            $table->dropColumn("CLNT_CMNT");
        });
    }
}
