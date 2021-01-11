<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateGenAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gen_accounts_title', function (Blueprint $table) {
            //asset - fixed asset - other
            $table->bigIncrements('id');
            $table->string('GNTL_NAME')->unique();
            $table->integer('GNTL_SORT')->default(5);   //0 - 10
        });

        DB::table('gen_accounts_title')->insert([
            'GNTL_NAME' =>  'Fixed',
            'GNTL_SORT' =>  '100',
        ]);

        DB::table('gen_accounts_title')->insert([
            'GNTL_NAME' =>  'Current',
            'GNTL_SORT' =>  '200',
        ]);

        DB::table('gen_accounts_title')->insert([
            'GNTL_NAME' =>  'Other',
            'GNTL_SORT' =>  '300',
        ]);

        DB::table('gen_accounts_title')->insert([
            'GNTL_NAME' =>  'Owner Equity',
            'GNTL_SORT' =>  '400',
        ]);

        DB::table('gen_accounts_title')->insert([
            'GNTL_NAME' =>  'Current Liability',
            'GNTL_SORT' =>  '500',
        ]);

        Schema::create('gen_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('GNAC_GNTL_ID');
            $table->foreign('GNAC_GNTL_ID')->references('id')->on('gen_accounts_title');
            $table->integer('GNAC_NATR');   //1 debit - 2 credit
            $table->string('GNAC_NAME');
            $table->string('GNAC_ARBC_NAME')->nullable();
            $table->decimal('GNAC_BLNC')->default(0);
            $table->string('GNAC_DESC')->nullable();
        });

        Schema::create('gen_accounts_trans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('GNTR_GNAC_ID');
            $table->foreign('GNTR_GNAC_ID')->references('id')->on('gen_accounts');
            $table->decimal("GNTR_DEBT")->default(0);
            $table->decimal("GNTR_CRDT")->default(0);
            $table->dateTime("GNTR_DATE");
            $table->decimal("GNTR_GNAC_BLNC");
            $table->string("GNTR_TTLE");
            $table->text("GNTR_CMNT")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gen_accounts_trans');
        Schema::dropIfExists('gen_accounts');
        Schema::dropIfExists('gen_accounts_title');
    }
}
