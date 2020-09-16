<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Models extends Model
{
    ///////////////////////////////////Colors/////////////////////////////////////////
    static function getColors(){
        return DB::table('colors')->get();
    }

    static function getColor($id){
        return DB::table('colors')->find($id);
    }

    static function insertColor($name, $code){
        return DB::table('colors')->insertGetId([
            "COLR_NAME" => $name,
            "COLR_CODE" => $code
        ]);
    }

    static function updateColor($id, $name, $code){
        return DB::table('colors')->where('id', $id)->update([
            "COLR_NAME" => $name,
            "COLR_CODE" => $code
        ]);
    }


    ////////////////////////////////////RAW Materials/////////////////////////////////
    static function getRawMaterials(){
        return DB::table('raw')->get();
    }

    static function getRawMaterial($id){
        return DB::table('raw')->find($id);
    }

    static function insertRawMaterial($name){
        return DB::table('raw')->insertGetId([
            "RAW_NAME" => $name
        ]);
    }

    static function updateRawMaterial($id, $name){
        return DB::table('raw')->where("id", $id)->update([
            "RAW_NAME" => $name
        ]);
    }

    ////////////////////////////////////TYPES/////////////////////////////////////////
    static function getTypes(){
        return DB::table('types')->join('raw', 'TYPS_RAW_ID', '=', 'raw.id')
                    ->select("types.*", 'RAW_NAME')
                    ->get();
    }

    static function getType($id){
        return DB::table('types')->join('raw', 'TYPS_RAW_ID', '=', 'raw.id')
                    ->select("types.*", 'RAW_NAME')
                    ->where('types.id', $id)
                    ->first();
    }

    static function insertType($name, $rawId){
        return DB::table('types')->insertGetId([
            "TYPS_NAME" => $name,
            "TYPS_RAW_ID" => $rawId
        ]);
    }

    static function updateType($id, $name, $rawId){
        return DB::table('types')->where("id", $id)->update([
            "TYPS_NAME" => $name,
            "TYPS_RAW_ID" => $rawId
        ]);
    }

    ////////////////////////////////////Models/////////////////////////////////////
    static function getModels(){
        return DB::table('models')
                    ->join('types', 'MODL_TYPS_ID', '=', 'types.id')
                    ->join('raw', 'types.TYPS_RAW_ID', '=', 'raw.id')
                    ->join('colors', 'MODL_COLR_ID', '=', 'colors.id')
                    ->join('suppliers', 'MODL_SUPP_ID', '=', 'suppliers.id')
                    ->select('models.*', 'types.TYPS_NAME', 'colors.COLR_NAME', 'colors.COLR_CODE', 'raw.RAW_NAME', 'suppliers.SUPP_NAME')
                    ->get();

    }

    static function getModelList(){
        return DB::table('models')
        ->join('types', 'MODL_TYPS_ID', '=', 'types.id')
        ->join('raw', 'types.TYPS_RAW_ID', '=', 'raw.id')
        ->join('colors', 'MODL_COLR_ID', '=', 'colors.id')
        ->select('models.id', 'models.MODL_NAME', 'models.MODL_UNID', 'types.TYPS_NAME', 'raw.RAW_NAME', 'colors.COLR_NAME', 'colors.COLR_CODE')
        ->get();
    }

    static function getModelNames(){
        return DB::table('models')
                    ->select('models.MODL_UNID', 'id')
                    ->whereNotNull('models.MODL_UNID')
                    ->get();

    }

    static function getModel($id){
        return DB::table('models')
                    ->join('types', 'MODL_TYPS_ID', '=', 'types.id')
                    ->join('raw', 'types.TYPS_RAW_ID', '=', 'raw.id')
                    ->join('suppliers', 'MODL_SUPP_ID', '=', 'suppliers.id')
                    ->join('colors', 'MODL_COLR_ID', '=', 'colors.id')
                    ->select('models.*', 'types.TYPS_NAME', 'raw.RAW_NAME', 'colors.COLR_NAME', 'colors.COLR_CODE', 'suppliers.SUPP_NAME')
                    ->where('models.id', $id)
                    ->first();

    }

    static function insertModel($name, $typeID, $colorID, $suppID, $price, $image=null, $serialID=null, $comment=null){
        return DB::table('models')->insertGetId([
            "MODL_NAME" => $name,
            "MODL_TYPS_ID" => $typeID,
            "MODL_COLR_ID" => $colorID,
            "MODL_SUPP_ID" => $suppID,
            "MODL_PRCE" => $price,
            "MODL_IMGE" => $image,
            "MODL_UNID" => $serialID,
            "MODL_CMNT" => $comment
        ]);
    }

    static function updateModel($id, $name, $typeID, $colorID, $suppID, $price, $image=null, $serialID=null, $comment=null, $oldPath=null){

        $updateArr = [
            "MODL_NAME" => $name,
            "MODL_TYPS_ID" => $typeID,
            "MODL_COLR_ID" => $colorID,
            "MODL_SUPP_ID" => $suppID,
            "MODL_PRCE" => $price,
            "MODL_UNID" => $serialID,
            "MODL_CMNT" => $comment
        ];

        if($image !== null && strcmp($image, '') != 0){
            $updateArr['MODL_IMGE']     =   $image; 
            if (file_exists($oldPath)){
                unlink($oldPath);
            }
        }

        return DB::table('models')->where('id', $id)->update($updateArr);
    }
}
