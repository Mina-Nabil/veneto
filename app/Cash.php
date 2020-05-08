<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Cash extends Model
{
    //Cash Model
    static function getTrans()
    {
        return DB::table('cash')->select('cash.*', 'trans_subtype.TRST_NAME', 'trans_type.TRTP_NAME')
            ->leftJoin('trans_subtype', 'CASH_TRST_ID', '=', 'trans_subtype.id')
            ->leftJoin('trans_type', 'trans_subtype.TRST_TRTP_ID', '=', 'trans_type.id')
            ->orderBy('id', 'desc')->limit(500)->get();
    }

    static function getReport($from, $to)
    {
        return DB::table('cash')->select('cash.*', 'trans_subtype.TRST_NAME', 'trans_type.TRTP_NAME')->leftJoin('trans_subtype', 'CASH_TRST_ID', '=', 'trans_subtype.id')
            ->leftJoin('trans_type', 'trans_subtype.TRST_TRTP_ID', '=', 'trans_type.id')->where([
                ['CASH_DATE', '>', $from],
                ['CASH_DATE', '<', date('Y-m-d', strtotime('+1 day', strtotime($to)))],
                ['CASH_EROR', 0]
            ])->orderBy('id', 'desc')->limit(500)->get();
    }

    static function insertTran($title, $in = 0, $out = 0, $comment = null, $isError = 0, $transType = null)
    {

        DB::transaction(function () use ($title, $in, $out, $comment, $isError, $transType) {

            $balance = self::getCashBalance() + $in - $out;
            DB::table('cash')->insertGetId([
                'CASH_NAME' => $title,
                'CASH_IN'   => $in,
                'CASH_OUT'  => $out,
                'CASH_BLNC' => $balance,
                'CASH_CMNT' => $comment,
                'CASH_EROR' => $isError,
                'CASH_TRST_ID' => $transType,
                'CASH_DATE' => date('Y-m-d H:i:s')
            ]);
        });
    }

    static function getCashBalance()
    {
        return DB::table('cash')->latest('id')->first()->CASH_BLNC;
    }

    static function correctFaultyTran($id)
    {
        $faulty = self::getOneRecord($id);
        if ($faulty == null || $faulty->CASH_EROR != 0) return 0;
        try {
            $exception = DB::transaction(function () use ($id, $faulty) {
                self::markTranError($id);
                //self::insertTran("Error Correction for TR#" . $id, $faulty->CASH_IN*-1, $faulty->CASH_OUT*-1, "Automated Transaction to correct Transaction number " . $id, 2);
            });
            return 1;
        } catch (Exception $e) {
            return 0;
        }
    }

    static function unmarkTranError($id)
    {
        $faulty = self::getOneRecord($id);
        if ($faulty == null || $faulty->CASH_EROR == 0) return 0;
        return DB::table("cash")->where('id', $id)->update([
            "CASH_EROR" => 0
        ]);
    }

    static private function getOneRecord($id)
    {
        return DB::table('cash')->find($id);
    }

    static private function markTranError($id)
    {
        return DB::table("cash")->where('id', $id)->update([
            "CASH_EROR" => 1
        ]);
    }
}
