<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTrasactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('client_trans', function(Blueprint $table){
            $table->string("CLTR_DESC")->nullable();
        });
        Schema::table('supplier_trans', function(Blueprint $table){
            $table->string("SPTR_DESC")->nullable();
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
        Schema::table('client_trans', function(Blueprint $table){
            $table->dropColumn('CLTR_DESC');
        });
        Schema::table('supplier_trans', function(Blueprint $table){
            $table->dropColumn('SPTR_DESC');
        });
    }
}
