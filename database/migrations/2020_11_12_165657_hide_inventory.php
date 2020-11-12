<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class HideInventory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('brands', function (Blueprint $table){
            $table->tinyInteger('BRND_HDDN')->default(0);
        });
        Schema::table('models', function (Blueprint $table){
            $table->tinyInteger('MODL_HDDN')->default(0);
        });
        Schema::table('finished', function (Blueprint $table){
            $table->tinyInteger('FNSH_HDDN')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('brands', function (Blueprint $table){
            $table->dropColumn('BRND_HDDN');
        });
        Schema::table('models', function (Blueprint $table){
            $table->tinyInteger('MODL_HDDN');
        });
        Schema::table('finished', function (Blueprint $table){
            $table->tinyInteger('FNSH_HDDN');
        });
    }
}
