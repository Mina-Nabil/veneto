<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('BANK_DATE');         
            $table->string('BANK_NAME');
            $table->double('BANK_IN')->default(0);
            $table->double('BANK_OUT')->default(0);
            $table->double('BANK_BLNC');
            $table->text('BANK_CMNT')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank');
    }
}
