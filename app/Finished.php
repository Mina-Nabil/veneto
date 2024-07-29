<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Finished extends Model
{
    //
    public static function getAvailable()
    {
        $ret = array();
        $ret['data'] = DB::table("finished")->leftjoin("models", "FNSH_MODL_ID", '=', 'models.id')
            ->leftjoin("brands", "FNSH_BRND_ID", '=', "brands.id")
            ->leftjoin("types", "MODL_TYPS_ID", '=', 'types.id')
            ->leftjoin("raw", "TYPS_RAW_ID", '=', 'raw.id')
            ->select("finished.*", "brands.BRND_NAME", "models.MODL_UNID", "models.MODL_IMGE", "types.TYPS_NAME", "raw.RAW_NAME")
            ->selectRaw("SUM(FNSH_36_AMNT + FNSH_38_AMNT + FNSH_40_AMNT + FNSH_42_AMNT + FNSH_44_AMNT + FNSH_46_AMNT + FNSH_48_AMNT + FNSH_50_AMNT + FNSH_52_AMNT) as itemsCount")
            ->where('FNSH_HDDN', 0)
            ->where([
                ["FNSH_36_AMNT", '!=', '0', 'or'],
                ["FNSH_38_AMNT", '!=', '0', 'or'],
                ["FNSH_40_AMNT", '!=', '0', 'or'],
                ["FNSH_42_AMNT", '!=', '0', 'or'],
                ["FNSH_44_AMNT", '!=', '0', 'or'],
                ["FNSH_46_AMNT", '!=', '0', 'or'],
                ["FNSH_48_AMNT", '!=', '0', 'or'],
                ["FNSH_50_AMNT", '!=', '0', 'or'],
                ["FNSH_52_AMNT", '!=', '0', 'or'],
                ])
            ->groupBy('finished.id')
   
            ->get();
        $ret['totals'] = DB::table("finished")->join("models", "FNSH_MODL_ID", '=', 'models.id')
            ->join("brands", "FNSH_BRND_ID", '=', "brands.id")
            ->selectRaw("SUM(FNSH_36_AMNT) as total36, SUM(FNSH_38_AMNT) as total38, SUM(FNSH_40_AMNT) as total40, SUM(FNSH_42_AMNT) as total42, SUM(FNSH_44_AMNT) as total44, SUM(FNSH_46_AMNT) as total46, SUM(FNSH_48_AMNT) as total48, SUM(FNSH_50_AMNT) as total50, SUM(FNSH_52_AMNT) as total52 ")
            ->where('FNSH_HDDN', 0)
            ->where([
                ["FNSH_36_AMNT", '!=', '0', 'or'],
                ["FNSH_38_AMNT", '!=', '0', 'or'],
                ["FNSH_40_AMNT", '!=', '0', 'or'],
                ["FNSH_42_AMNT", '!=', '0', 'or'],
                ["FNSH_44_AMNT", '!=', '0', 'or'],
                ["FNSH_46_AMNT", '!=', '0', 'or'],
                ["FNSH_48_AMNT", '!=', '0', 'or'],
                ["FNSH_50_AMNT", '!=', '0', 'or'],
                ["FNSH_52_AMNT", '!=', '0', 'or'],
                ])
            ->get()->first();
        return $ret;
    }
    public static function getAllFinished($isHidden = 0)
    {
        $ret['data'] = DB::table("finished")->join("models", "FNSH_MODL_ID", '=', 'models.id')
            ->join("brands", "FNSH_BRND_ID", '=', "brands.id")
            ->select("finished.*", "brands.BRND_NAME", "models.MODL_UNID");
        if (!$isHidden) {
            $ret['data'] = $ret['data']->where('FNSH_HDDN', 0);
        }
        $ret['data'] = $ret['data']->get();
        return $ret;
    }

    public static function getFinishedRow($modelID, $brandID)
    {
        return DB::table("finished")->where([
            ["FNSH_MODL_ID", '=',  $modelID],
            ["FNSH_BRND_ID", '=', $brandID]
        ])->first();
    }

    public static function insertFinishedEntry($entryArr)
    {
        DB::transaction(function () use ($entryArr) {
            foreach ($entryArr as $entry) {
                self::insertFinished($entry['model'] ?? -1, $entry['brand'] ?? -1, $entry['price'], $entry['amount36'], $entry['amount38'], $entry['amount40'], $entry['amount42'], $entry['amount44'], $entry['amount46'], $entry['amount48'], $entry['amount50'], $entry['finished'] ?? null, true);
            }
        });
    }

    public static function insertSoldEntry($entryArr, $isReturn = -1)
    {
        DB::transaction(function () use ($entryArr, $isReturn) {
            foreach ($entryArr as $entry) {
                self::insertFinished(null, null, $isReturn * $entry['price'], $isReturn * $entry['amount36'], $isReturn * $entry['amount38'], $isReturn * $entry['amount40'], $isReturn * $entry['amount42'], $isReturn * $entry['amount44'], $isReturn * $entry['amount46'], $isReturn * $entry['amount48'], $isReturn * $entry['amount50'], $entry['finished']);
            }
        });
    }

    public static function insertFinished($modelID, $brandID, $price = 0, $amount36 = 0, $amount38 = 0, $amount40 = 0, $amount42 = 0, $amount44 = 0, $amount46 = 0, $amount48 = 0, $amount50 = 0, $finished = null, $isUpdatePrice = false)
    {

        if ($finished == null) {
            $finished = self::getFinishedRow($modelID, $brandID);
            if ($finished !== null)
                $finished = $finished->id;
        }

        if ($finished == null) {
            $id = DB::table("finished")->insertGetId([
                "FNSH_MODL_ID" => $modelID,
                "FNSH_BRND_ID" => $brandID,
                "FNSH_36_AMNT" => $amount36,
                "FNSH_38_AMNT" => $amount38,
                "FNSH_40_AMNT" => $amount40,
                "FNSH_42_AMNT" => $amount42,
                "FNSH_44_AMNT" => $amount44,
                "FNSH_46_AMNT" => $amount46,
                "FNSH_48_AMNT" => $amount48,
                "FNSH_50_AMNT" => $amount50,
                "FNSH_PRCE" => $price
            ]);
            return $id;
        } else {
            if ($amount36 != 0)
                DB::table("finished")->where("id", $finished)->increment("FNSH_36_AMNT", $amount36);
            if ($amount38 != 0)
                DB::table("finished")->where("id", $finished)->increment("FNSH_38_AMNT", $amount38);
            if ($amount40 != 0)
                DB::table("finished")->where("id", $finished)->increment("FNSH_40_AMNT", $amount40);
            if ($amount42 != 0)
                DB::table("finished")->where("id", $finished)->increment("FNSH_42_AMNT", $amount42);
            if ($amount44 != 0)
                DB::table("finished")->where("id", $finished)->increment("FNSH_44_AMNT", $amount44);
            if ($amount46 != 0)
                DB::table("finished")->where("id", $finished)->increment("FNSH_46_AMNT", $amount46);
            if ($amount48 != 0)
                DB::table("finished")->where("id", $finished)->increment("FNSH_48_AMNT", $amount48);
            if ($amount50 != 0)
                DB::table("finished")->where("id", $finished)->increment("FNSH_50_AMNT", $amount50);
            if ($price > 0 && is_numeric($price) && $isUpdatePrice)
                DB::table("finished")->where('id', '=', $finished)->update([
                    "FNSH_PRCE" => $price
                ]);
        }
    }

    public static function updatePrice($id, $price)
    {
        return DB::table("finished")->where('id', '=', $id)->update([
            "FNSH_PRCE" => $price
        ]);
    }

    public static function emptyInventory($id)
    {
        return DB::table("finished")->where('id', '=', $id)->update([
            "FNSH_PRCE" => 0,
            "FNSH_36_AMNT" => 0,
            "FNSH_38_AMNT" => 0,
            "FNSH_40_AMNT" => 0,
            "FNSH_42_AMNT" => 0,
            "FNSH_44_AMNT" => 0,
            "FNSH_46_AMNT" => 0,
            "FNSH_48_AMNT" => 0,
            "FNSH_50_AMNT" => 0,
            "FNSH_52_AMNT" => 0
        ]);
    }

    public static function resetInventory()
    {
        return DB::table("finished")->update([
            "FNSH_PRCE" => 0,
            "FNSH_36_AMNT" => 0,
            "FNSH_38_AMNT" => 0,
            "FNSH_40_AMNT" => 0,
            "FNSH_42_AMNT" => 0,
            "FNSH_44_AMNT" => 0,
            "FNSH_46_AMNT" => 0,
            "FNSH_48_AMNT" => 0,
            "FNSH_50_AMNT" => 0,
            "FNSH_52_AMNT" => 0
        ]);
    }

    static function toggleHidden($id, $hidden)
    {
        return DB::table('finished')->where("id", $id)->update([
            "FNSH_HDDN" => $hidden
        ]);
    }

    static function hideAll()
    {
        return DB::table('finished')->update([
            "FNSH_HDDN" => 1
        ]);
    }
}
