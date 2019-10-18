<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRawTransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raw_trans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('RWTR_RINV_ID');
            $table->double('RWTR_AMNT_IN')->default(0);
            $table->double('RWTR_AMNT_OUT')->default(0);
            $table->dateTime('RWTR_DATE');
            $table->string('RWTR_FROM')->default('مخزن');
            $table->string('RWTR_TO')->default('تصنيع');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('raw_trans');
    }
}
