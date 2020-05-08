<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditLedgerTypeColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table("ledger", function (Blueprint $table) {
            $table->dropForeign("ledger_ldgr_trst_id_foreign");
            $table->renameColumn("LDGR_TRST_ID", "LDGR_LDST_ID");
            $table->foreign("LDGR_LDST_ID")->references("id")->on("ledger_subtype");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("ledger", function (Blueprint $table) {
            $table->dropForeign("ledger_ldgr_ldst_id_foreign");
            $table->renameColumn("LDGR_LDST_ID", "LDGR_TRST_ID");
            $table->foreign("LDGR_TRST_ID")->references("id")->on("trans_subtype");
        });
    }
}
