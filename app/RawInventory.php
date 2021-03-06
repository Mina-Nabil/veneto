<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Suppliers;
use App\Models;

class RawInventory extends Model
{

    ///////////////////////////////////////Raw Inventory Transaction///////////////////////////////
    static function getTransactions()
    {
        return DB::table("raw_trans")->join('raw_inventory', 'RWTR_RINV_ID', '=', 'raw_inventory.id')
            ->join('models', 'RINV_MODL_ID', '=', 'models.id')
            ->join('types', 'MODL_TYPS_ID', '=', 'types.id')
            ->join('colors', 'MODL_COLR_ID', '=', 'colors.id')
            ->join('suppliers', 'MODL_SUPP_ID', '=', 'suppliers.id')
            ->join('raw', 'TYPS_RAW_ID', '=', 'raw.id')
            ->select('raw_trans.*', 'raw_inventory.RINV_METR', 'raw_inventory.RINV_TRNS', 'models.MODL_NAME', 'models.MODL_UNID', 'models.MODL_IMGE', 'raw.RAW_NAME', 'colors.COLR_NAME', 'colors.COLR_CODE', 'types.TYPS_NAME', 'suppliers.SUPP_NAME')
            ->limit(500)
            ->orderBy('id', 'desc')
            ->get();
    }

    static function insertTransaction($rawInventory, $in, $out, $updateBalance = false, $from = null, $to = null)
    {

        DB::transaction(function () use ($rawInventory, $in, $out, $updateBalance, $from, $to) {

            $insertArr = [
                "RWTR_AMNT_IN"   => $in,
                "RWTR_AMNT_OUT"  => $out,
                "RWTR_DATE" => date("Y-m-d H:i:s"),
                "RWTR_RINV_ID"  =>   $rawInventory
            ];
            if ($from !== null)
                $insertArr["RWTR_FROM"] = $from;
            if ($to !== null)
                $insertArr["RWTR_TO"] = $to;

            DB::table("raw_trans")->insert($insertArr);

            if ($updateBalance) {
                if ($in > 0) self::incrementBalance($rawInventory, $in);
                if ($out > 0) self::decrementBalance($rawInventory, $out);
            }
        });
    }

    static function incrementRaw($id, $amount)
    {
        DB::transaction(function () use ($id, $amount) {
            $raw = self::getRaw($id);
            $model = Models::getModel($raw->RINV_MODL_ID);


            DB::table("raw_inventory")->where('id', $id)->increment("RINV_METR", $amount);


            self::insertTransaction($raw->id, 0, $amount, false, "تعديل", "مخزن");
            Suppliers::insertTrans($model->MODL_SUPP_ID, $model->MODL_PRCE * $amount, 0, 0, 0, 0, "Inventory Entry Edit - Total Number of items entered", "Entry Serial " . $raw->RINV_TRNS);
        });
    }
    /////////////////////////////////////////Production functions////////////////////////////////////////////
    static function getProduction()
    {
        return DB::table("raw_inventory")->join('models', 'RINV_MODL_ID', '=', 'models.id')
            ->join('types', 'MODL_TYPS_ID', '=', 'types.id')
            ->join('colors', 'MODL_COLR_ID', '=', 'colors.id')
            ->join('raw', 'TYPS_RAW_ID', '=', 'raw.id')
            ->join('suppliers', 'MODL_SUPP_ID', '=', 'suppliers.id')
            ->select('raw_inventory.*', 'models.MODL_NAME', 'models.MODL_IMGE', 'raw.RAW_NAME', 'colors.COLR_NAME', 'colors.COLR_CODE', 'suppliers.SUPP_NAME', 'types.TYPS_NAME', 'models.MODL_PRCE', 'models.MODL_UNID')
            ->where('RINV_PROD_AMNT', '>', '0')
            ->get();
    }

    static function incrementProduction($id, $amount = null)
    {
        DB::transaction(function () use ($id, $amount) {
            $raw = self::getRaw($id);
            $amount = ($amount ===  null) ? $raw->RINV_METR : $amount;

            DB::table("raw_inventory")->where('id', $id)
                ->increment("RINV_PROD_AMNT", $amount);

            self::insertTransaction($raw->id, 0, $amount, true, "مخزن", "تصنيع");
        });
    }

    static function decrementProduction($id, $amount, $returnRaw)
    {

        DB::transaction(function () use ($id, $amount, $returnRaw) {

            DB::table("raw_inventory")->where('id', $id)
                ->decrement("RINV_PROD_AMNT", $amount);


            self::insertTransaction($id, $amount, 0, $returnRaw, "تصنيع", ($returnRaw) ? "مخزن" : "جاهز");
        });
    }




    /////////////////////////////////////////Raw Inventory////////////////////////////////////////////
    static function getAvailable()
    {
        return DB::table("raw_inventory")->join('models', 'RINV_MODL_ID', '=', 'models.id')
            ->join('types', 'MODL_TYPS_ID', '=', 'types.id')
            ->join('colors', 'MODL_COLR_ID', '=', 'colors.id')
            ->join('raw', 'TYPS_RAW_ID', '=', 'raw.id')
            ->join('suppliers', 'MODL_SUPP_ID', '=', 'suppliers.id')
            ->select(DB::raw('COUNT(raw_inventory.id) as rolls'), 'raw.RAW_NAME', 'suppliers.SUPP_NAME', 'types.TYPS_NAME', DB::raw('SUM(raw_inventory.RINV_METR) as meters'), DB::raw('SUM(raw_inventory.RINV_PROD_AMNT) as amount'), 'MODL_SUPP_ID', 'types.id as TYPS_ID', 'raw.id as RAW_ID')
            ->groupBy('raw.id', 'MODL_SUPP_ID', 'types.id')
            ->where('RINV_METR', '>', '0')
            ->get();
    }

    static function getFullInventory()
    {
        return DB::table("raw_inventory")->join('models', 'RINV_MODL_ID', '=', 'models.id')
            ->join('types', 'MODL_TYPS_ID', '=', 'types.id')
            ->join('colors', 'MODL_COLR_ID', '=', 'colors.id')
            ->join('raw', 'TYPS_RAW_ID', '=', 'raw.id')
            ->select('raw_inventory.*', 'models.MODL_NAME', 'raw.RAW_NAME', 'colors.COLR_NAME', 'colors.COLR_CODE', 'types.TYPS_NAME', 'models.MODL_UNID')
            ->where('RINV_METR', '>', '0')
            ->limit(1000)
            ->get();
    }

    static function getRollsByGroup($rawID, $suppID, $typeID)
    {
        return DB::table("raw_inventory")->join('models', 'RINV_MODL_ID', '=', 'models.id')
            ->join('types', 'MODL_TYPS_ID', '=', 'types.id')
            ->join('colors', 'MODL_COLR_ID', '=', 'colors.id')
            ->join('raw', 'TYPS_RAW_ID', '=', 'raw.id')
            ->join('suppliers', 'MODL_SUPP_ID', '=', 'suppliers.id')
            ->select('models.MODL_IMGE', 'raw.RAW_NAME', 'suppliers.SUPP_NAME', 'models.MODL_NAME', 'types.TYPS_NAME', 'models.MODL_CMNT', 'models.MODL_PRCE', 'models.MODL_UNID', 'colors.COLR_NAME', 'raw_inventory.*', 'colors.COLR_CODE', 'MODL_SUPP_ID', 'types.id as TYPS_ID', 'raw.id as RAW_ID')
            ->where([
                ["raw.id", $rawID],
                ["suppliers.id", $suppID],
                ["types.id", $typeID],
                ['RINV_METR', '>', '0']
            ])
            ->get();
    }

    static function getRollsByTran($tran)
    {
        return DB::table("raw_inventory")->join('models', 'RINV_MODL_ID', '=', 'models.id')
            ->join('types', 'MODL_TYPS_ID', '=', 'types.id')
            ->join('colors', 'MODL_COLR_ID', '=', 'colors.id')
            ->join('raw', 'TYPS_RAW_ID', '=', 'raw.id')
            ->join('suppliers', 'MODL_SUPP_ID', '=', 'suppliers.id')
            ->select('raw_inventory.*', 'models.MODL_NAME', 'raw.RAW_NAME', 'colors.COLR_NAME', 'colors.COLR_CODE', 'suppliers.SUPP_NAME', 'models.MODL_UNID', 'models.MODL_CMNT', 'models.MODL_IMGE', 'types.TYPS_NAME', 'models.id as RINV_MODL_ID', 'models.MODL_PRCE')
            ->where('RINV_TRNS', $tran)
            ->get();
    }

    static function getSuppOfTran($tran)
    {
        $query = DB::table("raw_inventory")->join('models', 'RINV_MODL_ID', '=', 'models.id')
            ->join('types', 'MODL_TYPS_ID', '=', 'types.id')
            ->join('colors', 'MODL_COLR_ID', '=', 'colors.id')
            ->join('raw', 'TYPS_RAW_ID', '=', 'raw.id')
            ->join('suppliers', 'MODL_SUPP_ID', '=', 'suppliers.id')
            ->select('suppliers.id')
            ->where('RINV_TRNS', $tran)
            ->get()->first();
        if(isset($query->id)){
            return $query->id;
        } else {
            return null;
        }
    }

    static function getRaw($id)
    {
        return DB::table("raw_inventory")->join('models', 'RINV_MODL_ID', '=', 'models.id')
            ->join('types', 'MODL_TYPS_ID', '=', 'types.id')
            ->join('colors', 'MODL_COLR_ID', '=', 'colors.id')
            ->join('raw', 'TYPS_RAW_ID', '=', 'raw.id')
            ->select('raw_inventory.*', 'models.MODL_NAME', 'models.MODL_UNID', 'raw.RAW_NAME', 'colors.COLR_NAME', 'colors.COLR_CODE')
            ->where('raw_inventory.id', $id)
            ->first();
    }

    static function insertEntry($entryArr, $totals, $tranNumber)
    {
        DB::transaction(function () use ($entryArr, $totals, $tranNumber) {
            $totalAmount = 0;
            foreach ($entryArr as $entry) {
                $modelID = Models::insertModel(
                    $entry['name'],
                    $entry['type'],
                    $entry['color'],
                    $entry['supplier'],
                    $entry['price'],
                    $entry['photo'],
                    $entry['serial'],
                    $entry['comment']
                );

                foreach ($entry['items'] as $key => $amount) {
                    self::insert($modelID, $amount, $tranNumber);
                    $totalAmount += $amount;
                }
            }

            Suppliers::insertTrans($entry['supplier'], $totals['totalPrice'], 0, 0, 0, 0, "Inventory Entry - Total Number of items entered: " . $totalAmount, "Entry Serial " . $tranNumber);
        });
    }

    static function insertOneModelOnOldTran($modelName, $modelType, $modelColor, $modelSupp, $modelPrice, $modelPhoto, $modelSerial, $modelComment, $tran, $rolls){
        DB::transaction(function () use ($modelName, $modelType, $modelColor, $modelSupp, $modelPrice, $modelPhoto, $modelSerial, $modelComment, $tran, $rolls) {
            $totalAmount = 0;

                $modelID = Models::insertModel(
                    $modelName,
                    $modelType,
                    $modelColor,
                    $modelSupp,
                    $modelPrice,
                    $modelPhoto,
                    $modelSerial,
                    $modelComment
                );

                foreach ($rolls as $key => $amount) {
                    self::insert($modelID, $amount, $tran);
                    $totalAmount += $amount;
                }
                Suppliers::insertTrans($modelSupp, $totalAmount, 0, 0, 0, 0, "Inventory Entry Edit - Total Number of items entered", "Entry Serial " . $tran);
            }

        );
    }

    static function insert($model, $amount, $tranNumber)
    {
        DB::transaction(function () use ($model, $amount, $tranNumber) {
            $id = DB::table('raw_inventory')->insertGetId([
                "RINV_ENTR_DATE"     =>  date("Y-m-d H:i:s"),
                "RINV_MODL_ID"  =>  $model,
                "RINV_TRNS"     =>  $tranNumber,
                "RINV_METR"     =>  $amount
            ]);

            self::insertTransaction($id, $amount, 0, false, "جديد", "مخزن");
        });
    }

    static function incrementBalance($id, $in)
    {
        DB::table('raw_inventory')->where('id', $id)->increment('RINV_METR', $in);
    }

    static function decrementBalance($id, $out)
    {
        DB::table('raw_inventory')->where('id', $id)->decrement('RINV_METR', $out);
    }
}
