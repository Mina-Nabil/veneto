<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Finished extends Model
{
    //
    public static function getAvailable(){
        DB::table("finished")->join("models", "FNSH_MODL_ID", '=', 'models.id')
                            ->join("brands", "FNSH_BRND_ID", '=', "brands.id")
                            ->select("finished.*", "brands.BRND_NAME", "models.MODL_UNID")
                            ->where(DB::raw("SUM(
                                FNSH_AMNT_36 +
                                FNSH_AMNT_38 +
                                FNSH_AMNT_40 +
                                FNSH_AMNT_42 +
                                FNSH_AMNT_44 +
                                FNSH_AMNT_46 +
                                FNSH_AMNT_48 +
                                FNSH_AMNT_50 +
                                FNSH_AMNT_52
                                ) > 0") );
    }

    public static function insertFinished($modelID, $brandID, $amount36 = 0, $amount38 = 0, $amount40 = 0, $amount42 = 0
                                            , $amount44 = 0, $amount46 = 0, $amount48 = 0, $amount50 = 0, $amount52 = 0){
                
                $id = DB::table("finished")->insertGetId([
                    "FNSH_MODL_ID" => $modelID, 
                    "FNSH_BRND_ID" => $brandID, 
                    "FNSH_AMNT_36" => $amount36, 
                    "FNSH_AMNT_38" => $amount38, 
                    "FNSH_AMNT_40" => $amount40, 
                    "FNSH_AMNT_42" => $amount42, 
                    "FNSH_AMNT_44" => $amount44, 
                    "FNSH_AMNT_46" => $amount46, 
                    "FNSH_AMNT_48" => $amount48, 
                    "FNSH_AMNT_50" => $amount50, 
                    "FNSH_AMNT_52" => $amount52, 
                ]);
                
    }
}
