<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Suppliers extends Model
{
    //
    static function getSuppliers(){
        return DB::table('suppliers')->select('suppliers.*', 'supplier_types.SPTP_NAME')
                                        ->join('supplier_types', 'suppliers.SUPP_SPTP_ID', '=', 'supplier_types.id')
                                        ->get();
    }

    static function getSupplier($id){
        return DB::table('suppliers')->select('suppliers.*', 'supplier_types.SPTP_NAME')
                                        ->join('supplier_types', 'suppliers.SUPP_SPTP_ID', '=', 'supplier_types.id')
                                        ->where('suppliers.id', $id)
                                        ->first();
    }

    static function insert($name, $arbcName, $type, $balance){
        return DB::table('suppliers')->insertGetId([
            "SUPP_NAME" => $name,
            "SUPP_ARBC_NAME" => $arbcName,
            "SUPP_SPTP_ID" => $type,
            "SUPP_BLNC" =>  $balance
        ]);
    }
    
    static function updateSupplier($id, $name, $arbcName, $type, $balance){
        
        return DB::table('suppliers')->where('id', $id)->update([
            "SUPP_NAME"         => $name,
            "SUPP_ARBC_NAME"    => $arbcName,
            "SUPP_SPTP_ID"      => $type,
            "SUPP_BLNC"         => $balance
        ]);
    }

    //////////////////////////////////Supplier Types//////////////////////////////////
    static function getTypes(){
        return DB::table("supplier_types")->get();
    }

    static function getType($id){
        return DB::table("supplier_types")->find($id);
    }

    static function insertType($name){
        return DB::table('supplier_types')->insertGetId([
            "SPTP_NAME" => $name
        ]);
    }

    static function editType($id, $name){
        return DB::table('supplier_types')->where('id', $id)
                        ->update([
                            "SPTP_NAME" => $name
                        ]);
    }


}
