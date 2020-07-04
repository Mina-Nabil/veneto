<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class TransType extends Model
{
    
    static function getTransSubTypes(){
        return DB::table('trans_subtype')->select("trans_subtype.*", "trans_type.TRTP_NAME")
        ->join('trans_type', 'TRST_TRTP_ID', '=', 'trans_type.id')->get();
    }
    
    static function getTransSubTypesByType($type){
        return DB::table('trans_subtype')->select("trans_subtype.*", "trans_type.TRTP_NAME")
        ->join('trans_type', 'TRST_TRTP_ID', '=', 'trans_type.id')
        ->where('TRST_TRTP_ID', '=', $type)
        ->get();
    }

    static function getTransSubType($id){
        return DB::table('trans_subtype')->find($id);
    }

    static function insertTransSubType($typeID, $name){
        return DB::table('trans_subtype')->insertGetId([
            "TRST_NAME" => $name,
            "TRST_TRTP_ID" => $typeID,
        ]);
    }

    static function updateTransSubType($id, $name, $typeID){
        return DB::table('trans_subtype')->where("id", $id)->update([
            "TRST_NAME" => $name,
            "TRST_TRTP_ID" => $typeID,
        ]);
    }
    
    ///////////trans type/////////////////
    static function getTransTypes(){
        return DB::table('trans_type')->get();
    }

    static function getTransType($id){
        return DB::table('trans_type')->find($id);
    }

    static function insertTransType($name){
        return DB::table('trans_type')->insertGetId([
            "TRTP_NAME" => $name
        ]);
    }

    static function updateTransTypes($id, $name){
        return DB::table('trans_type')->where("id", $id)->update([
            "TRTP_NAME" => $name
        ]);
    }
}
