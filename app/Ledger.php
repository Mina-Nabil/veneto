<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Ledger extends Model
{
    //Ledger Model
    static function getTrans($subtype){
        $baseQuery =  DB::table('ledger')->select('ledger.*', 'ledger_subtype.LDST_NAME', 'ledger_type.LDTP_NAME')
        ->leftJoin('ledger_subtype', 'LDGR_LDST_ID', '=', 'ledger_subtype.id')
        ->leftJoin('ledger_type', 'ledger_subtype.LDST_LDTP_ID', '=', 'ledger_type.id');
        if($subtype!=0 && is_numeric($subtype))
            $baseQuery = $baseQuery->where([["LDGR_LDST_ID", $subtype]]);
        return $baseQuery->orderBy('id', 'desc')->limit(500)->get();
    }

    static function getReport($from, $to, $subtype=0){

        $whereArr = [
            ['LDGR_DATE', '>', $from],
            ['LDGR_DATE', '<', date('Y-m-d', strtotime('+1 day', strtotime($to)))],
            ['LDGR_EROR', 0]
        ];

        if($subtype!=0)
            array_push($whereArr, ['LDGR_LDST_ID', $subtype]);

        return DB::table('ledger')->select('ledger.*', 'ledger_subtype.LDST_NAME', 'ledger_type.LDTP_NAME')
        ->leftJoin('ledger_subtype', 'LDGR_LDST_ID', '=', 'ledger_subtype.id')
        ->leftJoin('ledger_type', 'ledger_subtype.LDST_LDTP_ID', '=', 'ledger_type.id')->where($whereArr)->orderBy('id', 'desc')->limit(500)->get();
    }

    static function insertTran($title, $in=0, $out=0, $comment=null, $isError=0, $transType){

        DB::transaction(function () use ($title, $in, $out, $comment, $isError, $transType) {

            $balance = self::getLedgerBalance($transType) - $in + $out;
            DB::table('ledger')->insertGetId([
                'LDGR_NAME' => $title,
                'LDGR_IN'   => $in,
                'LDGR_OUT'  => $out,
                'LDGR_BLNC' => $balance,
                'LDGR_CMNT' => $comment,
                'LDGR_EROR' => $isError,
                'LDGR_LDST_ID' => $transType,
                'LDGR_DATE' => date('Y-m-d H:i:s')
            ]);

            Cash::insertTran("عمليه حساب عام" . "(" . $title . ")", $out, $in, "Automatic entry from General Ledger");
        });
    }

    static function getLedgerBalance($subtype){

        $latestRow = DB::table("ledger")->where([[
            "LDGR_LDST_ID", $subtype
        ]])->orderByDesc("id")->first();
        if(isset($latestRow->LDGR_BLNC) && $latestRow->LDGR_BLNC!=0)
            return $latestRow->LDGR_BLNC;
        else 
            return 0;
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
