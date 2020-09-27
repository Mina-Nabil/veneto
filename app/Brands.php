<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Brands extends Model
{
    //
    static function getBrands(){
        return DB::table('brands')->get();
    }

    static function getBrand($id){
        return DB::table('brands')->find($id);
    }

    static function getBrandIDByName($name){
        return DB::table('brands')->where('BRND_NAME', '=', $name)->get()->first()->id ?? NULL;
    }

    static function insertBrand($name){
        return DB::table('brands')->insertGetId([
            "BRND_NAME" => $name
        ]);
    }

    static function updateBrand($id, $name){
        return DB::table('brands')->where("id", $id)->update([
            "BRND_NAME" => $name
        ]);
    }


}
