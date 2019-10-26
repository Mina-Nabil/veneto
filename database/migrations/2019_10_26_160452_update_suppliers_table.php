<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('suppliers', function (Blueprint $table){
            $table->string("SUPP_ADRS")->nullable();
            $table->string("SUPP_TELE")->nullable();
            $table->string("SUPP_CMNT")->nullable();
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
        Schema::table('suppliers', function(Blueprint $table){
            $table->dropColumn("SUPP_ADRS");
            $table->dropColumn("SUPP_TELE");
            $table->dropColumn("SUPP_CMNT");
        });
    }
}
