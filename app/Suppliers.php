<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

use App\Cash;
use App\Bank;

class Suppliers extends Model
{
    ///////////////////////Reports/////////////////////
    static function getAccountStatement($suppID, $from, $to)
    {
        $ret = array();

        $ret['trans'] = DB::table("supplier_trans")->join('suppliers', "SPTR_SUPP_ID", "=", "suppliers.id")
            ->select("supplier_trans.*", "suppliers.SUPP_NAME", "suppliers.SUPP_ARBC_NAME")
            ->where([
                ["SPTR_SUPP_ID", '=', $suppID],
                ["SPTR_DATE", '>=', $from],
                ["SPTR_EROR", 0],
                ["SPTR_DATE", '<=',  date('Y-m-d', strtotime('+1 day', strtotime($to)))]
            ])->orderBy('id', 'asc')->get();

        $ret['totals']  =   DB::table("supplier_trans")->selectRaw("SUM(SPTR_CASH_AMNT) as totalCash, SUM(SPTR_PRCH_AMNT) as totalPurch, 
                                                                    SUM(SPTR_DISC_AMNT) as totalDisc, SUM(SPTR_RTRN_AMNT) as totalReturn, SUM(SPTR_NTPY_AMNT) as totalNotes")
            ->where([
                ["SPTR_SUPP_ID", '=', $suppID],
                ["SPTR_DATE", '>=', $from],
                ["SPTR_DATE", '<=',  date('Y-m-d', strtotime('+1 day', strtotime($to)))]
            ])->groupBy("SPTR_SUPP_ID")->first();

        $ret['balance'] =   DB::table("supplier_trans")->select("SPTR_BLNC")->where([
            ["SPTR_SUPP_ID", '=', $suppID],
            ["SPTR_DATE", '>=', $from],
            ["SPTR_DATE", '<=',  date('Y-m-d', strtotime('+1 day', strtotime($to)))]
        ])
            ->orderBy('id', 'desc')->first()->SPTR_BLNC ?? 0;

        return $ret;
    }


    static function getTotals($from, $to, $type = -1)
    {
        $ret = array();
        $to = date('Y-m-d', strtotime('+1 day', strtotime($to)));
        $ret['data'] =   DB::table("supplier_trans")->join('suppliers', "SPTR_SUPP_ID", "=", "suppliers.id")
            ->select("suppliers.SUPP_NAME", "suppliers.id")
            ->selectRaw("SUM(SPTR_CASH_AMNT) as totalCash, SUM(SPTR_PRCH_AMNT) as totalPurch, 
                                                         SUM(SPTR_DISC_AMNT) as totalDisc, SUM(SPTR_RTRN_AMNT) as totalReturn, SUM(SPTR_NTPY_AMNT) as totalNotes")
            ->whereBetween("SPTR_DATE", [$from, $to]);
        if ($type != -1) {
            $ret['data'] = $ret['data']->where('SUPP_SPTP_ID', '=', $type);
        }

        $ret['data'] = $ret['data']->groupBy("SPTR_SUPP_ID")->get();

        $balancesWhereString = "t2.SPTR_DATE >= '{$from}' AND t2.SPTR_DATE <= '{$to} '";
        if ($type != -1) {
            $balancesWhereString .= " AND SUPP_SPTP_ID = " . $type . ' ';
        }

        $balances = DB::table("supplier_trans as t1")->selectRaw("t1.id, SPTR_SUPP_ID , SPTR_BLNC , SPTR_DATE")
            ->havingRaw("id = (SELECT max(t2.id) from supplier_trans as t2 JOIN suppliers ON t2.SPTR_SUPP_ID = suppliers.id WHERE  {$balancesWhereString} ) ")
            ->get();
      
        $ret['balances'] = [];
        foreach ($balances as $balance) {
            $ret['balances'][$balance->SPTR_SUPP_ID] = $balance->SPTR_BLNC;
        }

        $ret['others'] = DB::table("suppliers as t1")->join('supplier_trans', "SPTR_SUPP_ID", "=", "t1.id")
            ->select(['t1.id', 'SPTR_BLNC', 'SUPP_NAME'])->whereNotIn('t1.id', $balances->pluck('SPTR_SUPP_ID'))
            ->whereRaw(" supplier_trans.id = (SELECT MAX(id) FROM supplier_trans HAVING SPTR_SUPP_ID = t1.id AND SPTR_DATE <= '{$to}' ) ");

        if ($type == -1) {
            $ret['others'] = $ret['others']->get();
        } else {
            $ret['others'] = $ret['others']->where("SUPP_SPTP_ID", '=', $type)->get();
        }

        $ret['totals'] = DB::table("supplier_trans")->join('suppliers', "SPTR_SUPP_ID", "=", "suppliers.id")
            ->selectRaw("SUM(SPTR_CASH_AMNT) as totalCash, SUM(SPTR_PRCH_AMNT) as totalPurch, SUM(DISTINCT suppliers.SUPP_BLNC) as totalBalance, 
                                            SUM(SPTR_DISC_AMNT) as totalDisc, SUM(SPTR_RTRN_AMNT) as totalReturn, SUM(SPTR_NTPY_AMNT) as totalNotes")
            ->whereBetween("SPTR_DATE", [$from, $to]);

        if ($type != -1) {
            $ret['totals'] = $ret['totals']->where('SUPP_SPTP_ID', '=', $type);
        }
        $ret['totals'] = $ret['totals']->get()->first();


        foreach ($ret['others'] as $mloshTrans) {
            $ret['totals']->totalBalance += $mloshTrans->SPTR_BLNC;
        }
        //dd($ret['balances']);
        return $ret;
    }

    ///////////////////Transactions//////////////////////

    static function getTrans($suppID = null)
    {
        $query = DB::table("supplier_trans")->join('suppliers', "SPTR_SUPP_ID", "=", "suppliers.id")
            ->select("supplier_trans.*", "suppliers.SUPP_NAME", "suppliers.SUPP_ARBC_NAME");
        if ($suppID !== null)
            $query = $query->where("SPTR_SUPP_ID", $suppID);
        return $query->orderBy('id', 'asc')->limit(500)->get();
    }

    static function getLastTransaction($suppID)
    {
        return DB::table("supplier_trans")->where("SPTR_SUPP_ID", $suppID)->orderBy('id', 'desc')->first();
    }

    static function insertTrans($supp, $purchase, $cash, $notespay, $discount, $return, $comment, $desc = null)
    {

        $suppLastTrans  = self::getLastTransaction($supp);
        if ($suppLastTrans !== null) {

            $cashBalance    = $suppLastTrans->SPTR_CASH_BLNC;
            $discBalance    = $suppLastTrans->SPTR_DISC_BLNC;
            $notesBalance   = $suppLastTrans->SPTR_NTPY_BLNC;
            $prchBalance    = $suppLastTrans->SPTR_PRCH_BLNC;
            $returnBalance  = $suppLastTrans->SPTR_RTRN_BLNC;
            $oldBalance     = $suppLastTrans->SPTR_BLNC;
        } else {
            $cashBalance    = 0;
            $discBalance    = 0;
            $notesBalance   = 0;
            $prchBalance    = 0;
            $returnBalance  = 0;
            $oldBalance     = self::getSupplierBalance($supp);
        }

        DB::transaction(function () use (
            $supp,
            $purchase,
            $cash,
            $notespay,
            $discount,
            $return,
            $comment,
            $cashBalance,
            $discBalance,
            $prchBalance,
            $notesBalance,
            $returnBalance,
            $oldBalance,
            $desc
        ) {

            $newBalance     =   $oldBalance + $purchase - $cash - $notespay - $return - $discount;

            $id = DB::table("supplier_trans")->insertGetId([
                "SPTR_SUPP_ID"      => $supp,
                "SPTR_PRCH_AMNT"    => (float) $purchase,
                "SPTR_PRCH_BLNC"    => (float) $prchBalance + $purchase,
                "SPTR_CASH_AMNT"    => (float) $cash,
                "SPTR_CASH_BLNC"    => (float) $cashBalance + $cash,
                "SPTR_DISC_AMNT"    => (float) $discount,
                "SPTR_DISC_BLNC"    => (float) $discBalance + $discount,
                "SPTR_NTPY_AMNT"    => (float) $notespay,
                "SPTR_NTPY_BLNC"    => (float) $notesBalance + $notespay,
                "SPTR_RTRN_AMNT"    => (float) $return,
                "SPTR_RTRN_BLNC"    => (float) $returnBalance + $return,
                "SPTR_BLNC"         => $newBalance,
                "SPTR_Date"         => date("Y-m-d H:i:s"),
                "SPTR_CMNT"         =>  $comment,
                "SPTR_DESC"         =>  $desc
            ]);

            if ($cash != 0) {
                $supplier = self::getSupplier($supp);
                Cash::insertTran("Supplier ($supplier->SUPP_NAME) TRN.# " . $id, 0, $cash, $comment);
            }
            if ($notespay != 0) {
                $supplier = self::getSupplier($supp);
                Bank::insertTran("Supplier ($supplier->SUPP_NAME) TRN.# " . $id, 0, $notespay, $comment);
            }


            self::updateBalance($supp, $newBalance);
        });
    }




    static function correctFaultyTran($id)
    {
        $faulty = self::getOneRecord($id);
        if ($faulty == null || $faulty->SPTR_EROR != 0) return 0;
        try {
            $exception = DB::transaction(function () use ($id, $faulty) {
                self::markTranError($id);
                //self::insertTran("Error Correction for TR#" . $id, $faulty->SPTR_IN*-1, $faulty->SPTR_OUT*-1, "Automated Transaction to correct Transaction number " . $id, 2);
            });
            return 1;
        } catch (Exception $e) {
            return 0;
        }
    }

    static function unmarkTranError($id)
    {
        $faulty = self::getOneRecord($id);
        if ($faulty == null || $faulty->SPTR_EROR == 0) return 0;
        return DB::table("supplier_trans")->where('id', $id)->update([
            "SPTR_EROR" => 0
        ]);
    }

    static private function getOneRecord($id)
    {
        return DB::table('supplier_trans')->find($id);
    }

    static private function markTranError($id)
    {
        return DB::table("supplier_trans")->where('id', $id)->update([
            "SPTR_EROR" => 1
        ]);
    }



    //Suppliers Table CRUD
    static function getSuppliers($type = -1)
    {
        $query = DB::table('suppliers')->select('suppliers.*', 'supplier_types.SPTP_NAME')
            ->join('supplier_types', 'suppliers.SUPP_SPTP_ID', '=', 'supplier_types.id');
        if ($type != -1) {
            $query = $query->where('SUPP_SPTP_ID', '=', $type);
        }
        return    $query->get();
    }

    static function getSupplier($id)
    {
        return DB::table('suppliers')->select('suppliers.*', 'supplier_types.SPTP_NAME')
            ->join('supplier_types', 'suppliers.SUPP_SPTP_ID', '=', 'supplier_types.id')
            ->where('suppliers.id', $id)
            ->first();
    }

    static function insert($name, $arbcName, $type, $balance, $address = null, $tele = null, $comment = null)
    {
        return DB::table('suppliers')->insertGetId([
            "SUPP_NAME" => $name,
            "SUPP_ARBC_NAME" => $arbcName,
            "SUPP_SPTP_ID" => $type,
            "SUPP_ADRS" => $address,
            "SUPP_TELE" => $tele,
            "SUPP_CMNT" => $comment,
            "SUPP_BLNC" =>  $balance
        ]);
    }

    static function updateSupplier($id, $name, $arbcName, $type, $balance, $address = null, $tele = null, $comment = null)
    {

        return DB::table('suppliers')->where('id', $id)->update([
            "SUPP_NAME"         => $name,
            "SUPP_ARBC_NAME"    => $arbcName,
            "SUPP_SPTP_ID"      => $type,
            "SUPP_ADRS"         => $address,
            "SUPP_TELE"         => $tele,
            "SUPP_CMNT"         => $comment,
            "SUPP_BLNC"         => $balance
        ]);
    }

    static function updateBalance($suppID, $balance)
    {
        return DB::table('suppliers')->where('id', $suppID)->update([
            "SUPP_BLNC"         => $balance
        ]);
    }

    static function getSupplierBalance($suppID)
    {
        return DB::table('suppliers')->where('id', $suppID)->select("SUPP_BLNC")->first()->SUPP_BLNC;
    }

    static function getTotalBalance($type = -1)
    {
        $query = DB::table('suppliers');
        if ($type != -1) {
            $query = $query->where('SUPP_SPTP_ID', '=', $type);
        }
        return $query->sum('SUPP_BLNC');
    }

    //////////////////////////////////Supplier Types//////////////////////////////////
    static function getTypes()
    {
        return DB::table("supplier_types")->get();
    }

    static function getType($id)
    {
        return DB::table("supplier_types")->find($id);
    }

    static function insertType($name)
    {
        return DB::table('supplier_types')->insertGetId([
            "SPTP_NAME" => $name
        ]);
    }

    static function editType($id, $name)
    {
        return DB::table('supplier_types')->where('id', $id)
            ->update([
                "SPTP_NAME" => $name
            ]);
    }
}
