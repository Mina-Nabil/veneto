<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Cash extends Model
{
    //Cash Model
    static function getTrans(){
        return DB::table('cash')->orderBy('id', 'desc')->limit(500)->get();
    }

    static function getReport($from, $to){
        return DB::table('cash')->where([
            ['CASH_DATE', '>', $from],
            ['CASH_DATE', '<', date('Y-m-d', strtotime('+1 day', strtotime($to)))]
        ])->orderBy('id', 'desc')->limit(500)->get();
    }

    static function insertTran($title, $in=0, $out=0, $comment=null){

        DB::transaction(function () use ($title, $in, $out, $comment) {

            $balance = self::getCashBalance() + $in - $out;
            DB::table('cash')->insertGetId([
                'CASH_NAME' => $title,
                'CASH_IN'   => $in,
                'CASH_OUT'  => $out,
                'CASH_BLNC' => $balance,
                'CASH_CMNT' => $comment,
                'CASH_DATE' => date('Y-m-d H:i:s')
            ]);
        });
    }

    static function getCashBalance(){
        return DB::table('cash')->latest('id')->first()->CASH_BLNC;
    }
}
