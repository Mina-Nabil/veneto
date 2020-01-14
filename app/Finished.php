<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Finished extends Model
{
    //
    public static function getAvailable(){
        return DB::table("finished")->join("models", "FNSH_MODL_ID", '=', 'models.id')
                            ->join("brands", "FNSH_BRND_ID", '=', "brands.id")
                            ->select("finished.*", "brands.BRND_NAME", "models.MODL_UNID", "models.MODL_IMGE")
                            ->where("FNSH_36_AMNT"  , '!=', '0')
                            ->orWhere("FNSH_38_AMNT"  , '!=', '0' )   
                            ->orWhere("FNSH_40_AMNT"  , '!=', '0' )   
                            ->orWhere("FNSH_42_AMNT"  , '!=', '0' )   
                            ->orWhere("FNSH_44_AMNT"  , '!=', '0' )   
                            ->orWhere("FNSH_46_AMNT"  , '!=', '0' )   
                            ->orWhere("FNSH_48_AMNT"  , '!=', '0' )   
                            ->orWhere("FNSH_50_AMNT"  , '!=', '0' )   
                            ->orWhere("FNSH_52_AMNT"  , '!=', '0' )
                            ->get();
    }
    public static function getAllFinished(){
        DB::table("finished")->join("models", "FNSH_MODL_ID", '=', 'models.id')
                            ->join("brands", "FNSH_BRND_ID", '=', "brands.id")
                            ->select("finished.*", "brands.BRND_NAME", "models.MODL_UNID")
                            ->get();
    }

    public static function getFinishedRow($modelID, $brandID){
        return DB::table("finished")->where([
            ["FNSH_MODL_ID" , '=',  $modelID],
            ["FNSH_BRND_ID" , '=', $brandID]
            ])->first();
    }

    public static function insertFinishedEntry($entryArr){
        DB::transaction(function () use ($entryArr){
            foreach($entryArr as $entry){
                self::insertFinished($entry['model'], $entry['brand'], $entry['price'], $entry['amount36'], $entry['amount38'], $entry['amount40'], $entry['amount42'], $entry['amount44'], $entry['amount46'], $entry['amount48'], $entry['amount50']);
            }
        });
    }

    public static function insertSoldEntry($entryArr){
        DB::transaction(function () use ($entryArr){
            foreach($entryArr as $entry){
                self::insertFinished(null, null, -1*$entry['price'], -1*$entry['amount36'], -1*$entry['amount38'], -1*$entry['amount40'], -1*$entry['amount42'], -1*$entry['amount44'], -1*$entry['amount46'], -1*$entry['amount48'], -1*$entry['amount50'], $entry['finished']);
            }
        });
    }

    public static function insertFinished($modelID, $brandID, $price, $amount36 = 0, $amount38 = 0, $amount40 = 0, $amount42 = 0, $amount44 = 0, $amount46 = 0, $amount48 = 0, $amount50 = 0, $finished=null){

            if($finished == null){
                $finished = self::getFinishedRow($modelID, $brandID);
                if($finished !== null)
                    $finished = $finished->id;
            }
            
                if($finished == null){
                    return $id = DB::table("finished")->insertGetId([
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
                        "FNSH_PRCE" => $price, 
                    ]);
                }
                else {
                    if($amount36!=0)
                        DB::table("finished")->where("id", $finished)->increment("FNSH_36_AMNT", $amount36);
                    if($amount38!=0)
                        DB::table("finished")->where("id", $finished)->increment("FNSH_38_AMNT", $amount38);
                    if($amount40!=0)
                        DB::table("finished")->where("id", $finished)->increment("FNSH_40_AMNT", $amount40);
                    if($amount42!=0)
                        DB::table("finished")->where("id", $finished)->increment("FNSH_42_AMNT", $amount42);
                    if($amount44!=0)
                        DB::table("finished")->where("id", $finished)->increment("FNSH_44_AMNT", $amount44);
                    if($amount46!=0)
                        DB::table("finished")->where("id", $finished)->increment("FNSH_46_AMNT", $amount46);
                    if($amount48!=0)
                        DB::table("finished")->where("id", $finished)->increment("FNSH_48_AMNT", $amount48);
                    if($amount50!=0)
                        DB::table("finished")->where("id", $finished)->increment("FNSH_50_AMNT", $amount50);
                }
                
    }

    public static function updatePrice($id, $price){
        return DB::table("finished")->where('id', '=', $id)->update([
            "FNSH_PRCE" => $price
        ]);
    }
}
