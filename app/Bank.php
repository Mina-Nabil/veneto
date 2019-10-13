<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Bank extends Model
{
    //Bank Model
    static function getTrans(){
        return DB::table('bank')->orderBy('id', 'desc')->limit(500)->get();
    }

    static function getReport($from, $to){
        return DB::table('bank')->where([
            ['BANK_DATE', '>', $from],
            ['BANK_DATE', '<', date('Y-m-d', strtotime('+1 day', strtotime($to)))]
        ])->orderBy('id', 'desc')->limit(500)->get();
    }

    static function insertTran($title, $in=0, $out=0, $comment=null){

        DB::transaction(function () use ($title, $in, $out, $comment) {

            $balance = self::getBankBalance() + $in - $out;
            DB::table('bank')->insertGetId([
                'BANK_NAME' => $title,
                'BANK_IN'   => $in,
                'BANK_OUT'  => $out,
                'BANK_BLNC' => $balance,
                'BANK_CMNT' => $comment,
                'BANK_DATE' => date('Y-m-d H:i:s')
            ]);

        });


    }

    static function getBankBalance(){
        return DB::table('bank')->latest('id')->first()->BANK_BLNC;
    }
}
