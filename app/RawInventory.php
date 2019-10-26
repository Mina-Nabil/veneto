<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Suppliers;
use App\Models;

class RawInventory extends Model
{

    ///////////////////////////////////////Raw Inventory Transaction///////////////////////////////
    static function getTransactions(){
        return DB::table("raw_trans")->join('raw_inventory', 'RWTR_RINV_ID', '=', 'raw_inventory.id')
                                    ->join('models', 'RINV_MODL_ID', '=', 'models.id')
                                    ->join('types', 'MODL_TYPS_ID', '=', 'types.id')
                                    ->join('colors', 'MODL_COLR_ID', '=', 'colors.id')
                                    ->join('suppliers', 'MODL_SUPP_ID', '=', 'suppliers.id')
                                    ->join('raw', 'TYPS_RAW_ID', '=', 'raw.id')
                                    ->select('raw_trans.*','raw_inventory.RINV_METR', 'raw_inventory.RINV_TRNS', 'models.MODL_NAME', 'models.MODL_IMGE', 'raw.RAW_NAME', 'colors.COLR_NAME', 'colors.COLR_CODE', 'types.TYPS_NAME', 'suppliers.SUPP_NAME')
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
    /////////////////////////////////////////Production functions////////////////////////////////////////////
    static function getProduction(){
        return DB::table("raw_inventory")->join('models', 'RINV_MODL_ID', '=', 'models.id')
                                         ->join('types', 'MODL_TYPS_ID', '=', 'types.id')
                                         ->join('colors', 'MODL_COLR_ID', '=', 'colors.id')
                                         ->join('raw', 'TYPS_RAW_ID', '=', 'raw.id')
                                         ->join('suppliers', 'MODL_SUPP_ID', '=', 'suppliers.id')
                                         ->select('raw_inventory.*', 'models.MODL_NAME', 'models.MODL_IMGE', 'raw.RAW_NAME', 'colors.COLR_NAME', 'colors.COLR_CODE', 'suppliers.SUPP_NAME', 'types.TYPS_NAME', 'models.MODL_PRCE')
                                         ->where('RINV_PROD_AMNT', '>', '0')
                                         ->get();
    }

    static function incrementProduction($id, $amount=null){
        DB::transaction(function () use ($id, $amount){
            $raw = self::getRaw($id);
            $amount = ($amount ===  null) ? $raw->RINV_METR : $amount;

            DB::table("raw_inventory")->where('id', $id)
                    ->increment("RINV_PROD_AMNT", $amount);

            self::insertTransaction($raw->id, 0, $amount, true);

        });
    }




    /////////////////////////////////////////Raw Inventory////////////////////////////////////////////
    static function getAvailable(){
        return DB::table("raw_inventory")->join('models', 'RINV_MODL_ID', '=', 'models.id')
                                         ->join('types', 'MODL_TYPS_ID', '=', 'types.id')
                                         ->join('colors', 'MODL_COLR_ID', '=', 'colors.id')
                                         ->join('raw', 'TYPS_RAW_ID', '=', 'raw.id')
                                         ->join('suppliers', 'MODL_SUPP_ID', '=', 'suppliers.id')
                                         ->select(DB::raw('COUNT(raw_inventory.id) as rolls'), 'models.MODL_NAME', 'models.MODL_IMGE', 'raw.RAW_NAME', 'colors.COLR_NAME', 'colors.COLR_CODE', 'suppliers.SUPP_NAME', 'types.TYPS_NAME', DB::raw('SUM(raw_inventory.RINV_METR) as meters'), DB::raw('SUM(raw_inventory.RINV_PROD_AMNT) as amount'), 'models.id as RINV_MODL_ID', 'models.MODL_CMNT' )
                                         ->groupBy('models.id')
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

    static function getRollsByModel($modelID, $noProd=true){
        return DB::table("raw_inventory")
        ->where([
            ["RINV_MODL_ID", $modelID],
            ['RINV_METR', '>', '0']
            ])
        ->get();  
    }

    static function getRollsByTran($tran){
        return DB::table("raw_inventory")->join('models', 'RINV_MODL_ID', '=', 'models.id')
                                         ->join('types', 'MODL_TYPS_ID', '=', 'types.id')
                                         ->join('colors', 'MODL_COLR_ID', '=', 'colors.id')
                                         ->join('raw', 'TYPS_RAW_ID', '=', 'raw.id')
                                         ->join('suppliers', 'MODL_SUPP_ID', '=', 'suppliers.id')
                                         ->select('raw_inventory.*', 'models.MODL_NAME', 'raw.RAW_NAME', 'colors.COLR_NAME', 'colors.COLR_CODE', 'suppliers.SUPP_NAME', 'models.MODL_UNID', 'models.MODL_CMNT', 'models.MODL_IMGE', 'types.TYPS_NAME', 'models.id as RINV_MODL_ID', 'models.MODL_PRCE' )
                                         ->where('RINV_TRNS', $tran)
                                         ->get();
    }

    static function getRaw($id){
        return DB::table("raw_inventory")->join('models', 'RINV_MODL_ID', '=', 'models.id')
                                         ->join('types', 'MODL_TYPS_ID', '=', 'types.id')
                                         ->join('colors', 'MODL_COLR_ID', '=', 'colors.id')
                                         ->join('raw', 'TYPS_RAW_ID', '=', 'raw.id')
                                         ->select('raw_inventory.*', 'models.MODL_NAME', 'raw.RAW_NAME', 'colors.COLR_NAME', 'colors.COLR_CODE')
                                         ->where('raw_inventory.id', $id)
                                         ->first();
    }

    static function insertEntry($entryArr, $totals, $tranNumber){
        DB::transaction(function () use ($entryArr, $totals, $tranNumber){
            foreach($entryArr as $entry){
                $modelID = Models::insertModel($entry['name'], $entry['type'], $entry['color'], $entry['supplier'],
                                                $entry['price'], $entry['photo'], $entry['serial'], $entry['comment']);

                foreach($entry['items'] as $key => $amount){
                    self::insert($modelID, $amount, $tranNumber);
                }
            }

            Suppliers::insertTrans($entry['supplier'], $totals['totalPrice'], 0, 0, 0, 0, "Entry Serial " . $tranNumber);
        });
    }

    static function insert($model, $amount, $tranNumber){
        DB::transaction(function () use ($model, $amount, $tranNumber){
            $id = DB::table('raw_inventory')->insertGetId([
                "RINV_ENTR_DATE"     =>  date("Y-m-d H:i:s"),
                "RINV_MODL_ID"  =>  $model,
                "RINV_TRNS"     =>  $tranNumber,
                "RINV_METR"     =>  $amount
            ]);
            
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
