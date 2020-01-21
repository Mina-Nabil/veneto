<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

use App\Cash;
use App\Bank;

class Suppliers extends Model
{
    ///////////////////////Reports/////////////////////
    static function getAccountStatement($suppID, $from, $to){
        $ret = array();

        $ret['trans'] = DB::table("supplier_trans")->join('suppliers', "SPTR_SUPP_ID", "=", "suppliers.id")
                        ->select("supplier_trans.*", "suppliers.SUPP_NAME", "suppliers.SUPP_ARBC_NAME")
                        ->where([
                            ["SPTR_SUPP_ID", '=', $suppID],
                            ["SPTR_DATE", '>=', $from],
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
            ["SPTR_DATE", '<=',  date('Y-m-d', strtotime('+1 day', strtotime($to)))]])
            ->orderBy('id', 'desc')->first()->SPTR_BLNC ?? 0;   

        return $ret;
    }


    static function getTotals($from, $to){
        $ret = array();

        $ret['data'] =   DB::table("supplier_trans")->join('suppliers', "SPTR_SUPP_ID", "=", "suppliers.id")
                                            ->select( "suppliers.SUPP_NAME", "suppliers.SUPP_BLNC", "suppliers.id")
                                            ->selectRaw("SUM(SPTR_CASH_AMNT) as totalCash, SUM(SPTR_PRCH_AMNT) as totalPurch, 
                                                         SUM(SPTR_DISC_AMNT) as totalDisc, SUM(SPTR_RTRN_AMNT) as totalReturn, SUM(SPTR_NTPY_AMNT) as totalNotes")
                            ->where([
                                ["SPTR_DATE", '>=', $from],
                                ["SPTR_DATE", '<=',  date('Y-m-d', strtotime('+1 day', strtotime($to)))]
                            ])->groupBy("SPTR_SUPP_ID")->get();

        $ret['totals'] = DB::table("supplier_trans")->join('suppliers', "SPTR_SUPP_ID", "=", "suppliers.id")
                                ->selectRaw("SUM(SPTR_CASH_AMNT) as totalCash, SUM(SPTR_PRCH_AMNT) as totalPurch, SUM(DISTINCT suppliers.SUPP_BLNC) as totalBalance, 
                                            SUM(SPTR_DISC_AMNT) as totalDisc, SUM(SPTR_RTRN_AMNT) as totalReturn, SUM(SPTR_NTPY_AMNT) as totalNotes")
                                ->where([
                                ["SPTR_DATE", '>=', $from],
                                ["SPTR_DATE", '<=',  date('Y-m-d', strtotime('+1 day', strtotime($to)))]
                                ])->get()->first();                          
        return $ret;

        }

    ///////////////////Transactions//////////////////////
    static function getTrans($suppID=null){
        $query = DB::table("supplier_trans")->join('suppliers', "SPTR_SUPP_ID", "=", "suppliers.id")
                                    ->select("supplier_trans.*", "suppliers.SUPP_NAME", "suppliers.SUPP_ARBC_NAME");
        if($suppID !== null)
            $query = $query->where("SPTR_SUPP_ID", $suppID);
        return $query->orderBy('id', 'asc')->limit(500)->get();
        
    }

    static function getLastTransaction($suppID){
        return DB::table("supplier_trans")->where("SPTR_SUPP_ID", $suppID)->orderBy('id', 'desc')->first();
    }

    static function insertTrans($supp, $purchase, $cash, $notespay, $discount, $return, $comment, $desc=null){
        
        $suppLastTrans  = self::getLastTransaction($supp);
        if($suppLastTrans !== null) {
            
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

        DB::transaction(function () use ($supp, $purchase, $cash, $notespay, $discount, $return, $comment,
                                        $cashBalance, $discBalance, $prchBalance, $notesBalance, $returnBalance, $oldBalance, $desc)
                {

                    $newBalance     =   $oldBalance + $purchase - $cash - $notespay - $return - $discount;

                    $id = DB::table("supplier_trans")->insertGetId([
                        "SPTR_SUPP_ID"      => $supp,
                        "SPTR_PRCH_AMNT"    => (double) $purchase,
                        "SPTR_PRCH_BLNC"    => (double) $prchBalance + $purchase,
                        "SPTR_CASH_AMNT"    => (double) $cash,
                        "SPTR_CASH_BLNC"    => (double) $cashBalance + $cash,
                        "SPTR_DISC_AMNT"    => (double) $discount,
                        "SPTR_DISC_BLNC"    => (double) $discBalance + $discount,
                        "SPTR_NTPY_AMNT"    => (double) $notespay,
                        "SPTR_NTPY_BLNC"    => (double) $notesBalance + $notespay,
                        "SPTR_RTRN_AMNT"    => (double) $return,
                        "SPTR_RTRN_BLNC"    => (double) $returnBalance + $return,
                        "SPTR_BLNC"         => $newBalance,
                        "SPTR_Date"         => date("Y-m-d H:i:s"),
                        "SPTR_CMNT"         =>  $comment,
                        "SPTR_DESC"         =>  $desc
                    ]);

                    if($cash > 0){
                        $supplier = self::getSupplier($supp);
                        Cash::insertTran("Supplier ($supplier->SUPP_NAME) TRN.# " . $id , 0, $cash, $comment);
                    }
                    if($notespay > 0){
                        $supplier = self::getSupplier($supp);
                        Bank::insertTran("Supplier ($supplier->SUPP_NAME) TRN.# " . $id, 0, $notespay, $comment);
                    }
                    

                    self::updateBalance($supp, $newBalance);
        });
    }
    
    
    
    
    static function counterTrans($tranID){

    }
     
    
    
    //Suppliers Table CRUD
    static function getSuppliers(){
        return DB::table('suppliers')->select('suppliers.*', 'supplier_types.SPTP_NAME')
                                        ->join('supplier_types', 'suppliers.SUPP_SPTP_ID', '=', 'supplier_types.id')
                                        ->get();
    }

    static function getSupplier($id){
        return DB::table('suppliers')->select('suppliers.*', 'supplier_types.SPTP_NAME')
                                        ->join('supplier_types', 'suppliers.SUPP_SPTP_ID', '=', 'supplier_types.id')
                                        ->where('suppliers.id', $id)
                                        ->first();
    }

    static function insert($name, $arbcName, $type, $balance, $address=null, $tele=null, $comment=null){
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
    
    static function updateSupplier($id, $name, $arbcName, $type, $balance, $address=null, $tele=null, $comment=null){
        
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

    static function updateBalance($suppID, $balance){
        return DB::table('suppliers')->where('id', $suppID)->update([
            "SUPP_BLNC"         => $balance
        ]);
    }

    static function getSupplierBalance($suppID){
        return DB::table('suppliers')->where('id', $suppID)->select("SUPP_BLNC")->first()->SUPP_BLNC;
    }

    static function getTotalBalance(){
        return DB::table('suppliers')->sum('SUPP_BLNC');
    }

    //////////////////////////////////Supplier Types//////////////////////////////////
    static function getTypes(){
        return DB::table("supplier_types")->get();
    }

    static function getType($id){
        return DB::table("supplier_types")->find($id);
    }

    static function insertType($name){
        return DB::table('supplier_types')->insertGetId([
            "SPTP_NAME" => $name
        ]);
    }

    static function editType($id, $name){
        return DB::table('supplier_types')->where('id', $id)
                        ->update([
                            "SPTP_NAME" => $name
                        ]);
    }


}
