<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Ledger extends Model
{
    //Ledger Model
    static function getTrans(){
        return DB::table('ledger')->select('ledger.*', 'trans_subtype.TRST_NAME', 'trans_type.TRTP_NAME')
        ->leftJoin('trans_subtype', 'LDGR_TRST_ID', '=', 'trans_subtype.id')
        ->leftJoin('trans_type', 'trans_subtype.TRST_TRTP_ID', '=', 'trans_type.id')
        ->orderBy('id', 'desc')->limit(500)->get();
    }

    static function getReport($from, $to){
        return DB::table('ledger')->where([
            ['LDGR_DATE', '>', $from],
            ['LDGR_DATE', '<', date('Y-m-d', strtotime('+1 day', strtotime($to)))],
            ['LDGR_EROR', 0]
        ])->orderBy('id', 'desc')->limit(500)->get();
    }

    static function insertTran($title, $in=0, $out=0, $comment=null, $isError=0, $transType=null){

        DB::transaction(function () use ($title, $in, $out, $comment, $isError, $transType) {

            $balance = self::getLedgerBalance() - $in + $out;
            DB::table('ledger')->insertGetId([
                'LDGR_NAME' => $title,
                'LDGR_IN'   => $in,
                'LDGR_OUT'  => $out,
                'LDGR_BLNC' => $balance,
                'LDGR_CMNT' => $comment,
                'LDGR_EROR' => $isError,
                'LDGR_TRST_ID' => $transType,
                'LDGR_DATE' => date('Y-m-d H:i:s')
            ]);
        });
    }

    static function getLedgerBalance(){
        return DB::table('ledger')->latest('id')->first()->LDGR_BLNC;
    }

    static function correctFaultyTran($id){
        $faulty = self::getOneRecord($id);
        if($faulty==null || $faulty->LDGR_EROR!=0) return 0;
        try {
            $exception = DB::transaction(function () use ($id, $faulty) {
                self::markTranError($id);
                //self::insertTran("Error Correction for TR#" . $id, $faulty->LDGR_IN*-1, $faulty->LDGR_OUT*-1, "Automated Transaction to correct Transaction number " . $id, 2);
            });
            return 1;
        } catch (Exception $e){
            return 0;
        }
        
    }

    static function unmarkTranError($id){
        $faulty = self::getOneRecord($id);
        if($faulty==null || $faulty->LDGR_EROR==0) return 0;
        return DB::table("ledger")->where('id', $id)->update([
            "LDGR_EROR" => 0
        ]); 
    }

    static private function getOneRecord($id){
        return DB::table('ledger')->find($id);
    }

    static private function markTranError($id){
       return DB::table("ledger")->where('id', $id)->update([
           "LDGR_EROR" => 1
       ]);
    }
}
