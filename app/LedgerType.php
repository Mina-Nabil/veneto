<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LedgerType extends Model
{
    static function getLedgerSubTypes(){
        return DB::table('ledger_subtype')->select("ledger_subtype.*", "ledger_type.LDTP_NAME")
        ->join('ledger_type', 'LDST_LDTP_ID', '=', 'ledger_type.id')->get();
    }

    static function getLedgerSubType($id){
        return DB::table('ledger_subtype')->find($id);
    }

    static function insertLedgerSubType($typeID, $name){
        return DB::table('ledger_subtype')->insertGetId([
            "LDST_NAME" => $name,
            "LDST_LDTP_ID" => $typeID,
        ]);
    }

    static function updateLedgerSubType($id, $name, $typeID){
        return DB::table('ledger_subtype')->where("id", $id)->update([
            "LDST_NAME" => $name,
            "LDST_LDTP_ID" => $typeID,
        ]);
    }
    
    ///////////ledger type/////////////////
    static function getLedgerTypes(){
        return DB::table('ledger_type')->get();
    }

    static function getLedgerType($id){
        return DB::table('ledger_type')->find($id);
    }

    static function insertLedgerType($name){
        return DB::table('ledger_type')->insertGetId([
            "LDTP_NAME" => $name
        ]);
    }

    static function updateLedgerTypes($id, $name){
        return DB::table('ledger_type')->where("id", $id)->update([
            "LDTP_NAME" => $name
        ]);
    }
}
