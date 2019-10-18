<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Suppliers;

class RawInventory extends Model
{

    ///////////////////////////////////////Raw Inventory Transaction///////////////////////////////
    static function getTransactions(){
        return DB::table("raw_trans")->join('raw_inventory', 'RWTR_RINV_ID', '=', 'raw_inventory.id')
                                    ->join('models', 'RINV_MODL_ID', '=', 'models.id')
                                    ->join('types', 'MODL_TYPS_ID', '=', 'types.id')
                                    ->join('colors', 'MODL_COLR_ID', '=', 'colors.id')
                                    ->join('suppliers', 'RINV_SUPP_ID', '=', 'suppliers.id')
                                    ->join('raw', 'TYPS_RAW_ID', '=', 'raw.id')
                                    ->select('raw_trans.*','raw_inventory.RINV_METR', 'models.MODL_NAME', 'models.MODL_IMGE', 'raw.RAW_NAME', 'colors.COLR_NAME', 'colors.COLR_CODE', 'types.TYPS_NAME', 'suppliers.SUPP_NAME')
                                    ->limit(500)
                                    ->orderBy('id', 'desc')
                                    ->get();
    }

    static function insertTransaction($rawInventory, $in, $out, $updateBalance=false, $from=null, $to=null){

        DB::transaction(function () use ($rawInventory, $in, $out, $updateBalance, $from, $to){

            $insertArr = [
                "RWTR_AMNT_IN"   => $in,
                "RWTR_AMNT_OUT"  => $out,
                "RWTR_DATE" => date("Y-m-d H:i:s"),
                "RWTR_RINV_ID"  =>   $rawInventory
            ];
            if($from !== null)
                $insertArr["RWTR_FROM"] = $from;
            if($to !== null)
                $insertArr["RWTR_TO"] = $to;

            DB::table("raw_trans")->insert($insertArr);

            if($updateBalance){
                if($in>0) self::incrementBalance($rawInventory, $in);
                if($out>0) self::decrementBalance($rawInventory, $out);
            }
                

        });
    }




    /////////////////////////////////////////Raw Inventory////////////////////////////////////////////
    static function getAvailable(){
        return DB::table("raw_inventory")->join('models', 'RINV_MODL_ID', '=', 'models.id')
                                         ->join('types', 'MODL_TYPS_ID', '=', 'types.id')
                                         ->join('colors', 'MODL_COLR_ID', '=', 'colors.id')
                                         ->join('raw', 'TYPS_RAW_ID', '=', 'raw.id')
                                         ->join('suppliers', 'RINV_SUPP_ID', '=', 'suppliers.id')
                                         ->select('raw_inventory.*', 'models.MODL_NAME', 'models.MODL_IMGE', 'raw.RAW_NAME', 'colors.COLR_NAME', 'colors.COLR_CODE', 'suppliers.SUPP_NAME', 'types.TYPS_NAME')
                                         ->where('RINV_METR', '>', '0')
                                         ->get();
    }

    static function getFullInventory(){
        return DB::table("raw_inventory")->join('models', 'RINV_MODL_ID', '=', 'models.id')
                                         ->join('types', 'MODL_TYPS_ID', '=', 'types.id')
                                         ->join('colors', 'MODL_COLR_ID', '=', 'colors.id')
                                         ->join('raw', 'TYPS_RAW_ID', '=', 'raw.id')
                                         ->select('raw_inventory.*', 'models.MODL_NAME', 'raw.RAW_NAME', 'colors.COLR_NAME', 'colors.COLR_CODE')
                                         ->limit(1000)
                                         ->get();
    }

    static function insert($model, $supplier, $amount, $price, $discount=0){
        DB::transaction(function () use ($model, $supplier, $amount, $price, $discount){
            $id = DB::table('raw_inventory')->insertGetId([
                "RINV_ENTR_DATE"     =>  date("Y-m-d H:i:s"),
                "RINV_MODL_ID"  =>  $model,
                "RINV_SUPP_ID"  =>  $supplier,
                "RINV_METR"     =>  $amount,
                "RINV_PRCE"     =>  $price 
            ]);
            
        Suppliers::insertTrans($supplier, $amount * $price, 0, 0, $discount, 0, "Raw Inventory Insert");
        self::insertTransaction($id, $amount, 0, false, "جديد", "مخزن");
        });
    }

    static function incrementBalance($id, $in){
        DB::table('raw_inventory')->where('id', $id)->increment('RINV_METR', $in);
    }

    static function decrementBalance($id, $out){
        DB::table('raw_inventory')->where('id', $id)->decrement('RINV_METR', $out);
    }
}
