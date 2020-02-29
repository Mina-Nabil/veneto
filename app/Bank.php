<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Bank extends Model
{
    //Bank Model
    static function getTrans(){
        return DB::table('bank')->select('bank.*', 'trans_subtype.TRST_NAME', 'trans_type.TRTP_NAME')
        ->leftJoin('trans_subtype', 'BANK_TRST_ID', '=', 'trans_subtype.id')
        ->leftJoin('trans_type', 'trans_subtype.TRST_TRTP_ID', '=', 'trans_type.id')
        ->orderBy('id', 'desc')->limit(500)->get();
    }

    static function getReport($from, $to){
        return DB::table('bank')->where([
            ['BANK_DATE', '>', $from],
            ['BANK_DATE', '<', date('Y-m-d', strtotime('+1 day', strtotime($to)))],
            ['BANK_EROR', 0]
        ])->orderBy('id', 'desc')->limit(500)->get();
    }

    static function insertTran($title, $in=0, $out=0, $comment=null, $isError=0, $transType=null){

        DB::transaction(function () use ($title, $in, $out, $comment, $isError, $transType) {

            $balance = self::getBankBalance() + $in - $out;
            DB::table('bank')->insertGetId([
                'BANK_NAME' => $title,
                'BANK_IN'   => $in,
                'BANK_OUT'  => $out,
                'BANK_BLNC' => $balance,
                'BANK_CMNT' => $comment,
                'BANK_EROR' => $isError,
                'BANK_TRST_ID' => $transType,
                'BANK_DATE' => date('Y-m-d H:i:s')
            ]);

        });


    }

    static function getBankBalance(){
        return DB::table('bank')->latest('id')->first()->BANK_BLNC;
    }

    static function correctFaultyTran($id){
        $faulty = self::getOneRecord($id);
        if($faulty==null || $faulty->BANK_EROR!=0) return 0;
        try {
            $exception = DB::transaction(function () use ($id, $faulty) {
                self::markTranError($id);
                //self::insertTran("Error Correction for TR#" . $id, $faulty->BANK_IN*-1, $faulty->BANK_OUT*-1, "Automated Transaction to correct Transaction number " . $id, 2);
            });
            return 1;
        } catch (Exception $e){
            return 0;
        }
        
    }

    static function unmarkTranError($id){
        $faulty = self::getOneRecord($id);
        if($faulty==null || $faulty->BANK_EROR==0) return 0;
        return DB::table("bank")->where('id', $id)->update([
            "BANK_EROR" => 0
        ]); 
    }

    static private function getOneRecord($id){
        return DB::table('bank')->find($id);
    }

    static private function markTranError($id){
       return DB::table("bank")->where('id', $id)->update([
           "BANK_EROR" => 1
       ]);
    }
}
