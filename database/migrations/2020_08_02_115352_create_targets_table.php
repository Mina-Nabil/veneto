<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('targets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('TRGT_CLNT_ID');
            $table->foreign("TRGT_CLNT_ID")->references("id")->on("clients");
            $table->unsignedInteger('TRGT_YEAR');
            $table->unsignedInteger('TRGT_MNTH');
            $table->double('TRGT_MONY')->default(0);
            $table->double('TRGT_BANK')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('targets');
    }
}
