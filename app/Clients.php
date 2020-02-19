<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

use App\Cash;
use App\Bank;

class Clients extends Model
{
    ///////////////////////Reports/////////////////////
    static function getAccountStatement($clientID, $from, $to){
        $ret = array();

        $ret['trans'] = DB::table("client_trans")->join('clients', "CLTR_CLNT_ID", "=", "clients.id")
                        ->select("client_trans.*", "clients.CLNT_NAME", "clients.CLNT_ARBC_NAME")
                        ->where([
                            ["CLTR_CLNT_ID", '=', $clientID],
                            ["CLTR_DATE", '>=', $from],
                            ["CLTR_DATE", '<=',  date('Y-m-d', strtotime('+1 day', strtotime($to)))],
                            ["CLTR_EROR", 0]
                        ])->orderBy('id', 'asc')->get();

        $ret['totals']  =   DB::table("client_trans")->selectRaw(" SUM(CLTR_CASH_AMNT) as totalCash, SUM(CLTR_SALS_AMNT) as totalPurch, 
                                                                    SUM(CLTR_DISC_AMNT) as totalDisc, SUM(CLTR_RTRN_AMNT) as totalReturn, SUM(CLTR_NTPY_AMNT) as totalNotes")
                            ->where([
                                ["CLTR_CLNT_ID", '=', $clientID],
                                ["CLTR_DATE", '>=', $from],
                                ["CLTR_DATE", '<=',  date('Y-m-d', strtotime('+1 day', strtotime($to)))]
                            ])->groupBy("CLTR_CLNT_ID")->first();

        $ret['balance'] =   DB::table("client_trans")->select("CLTR_BLNC")->where([
            ["CLTR_CLNT_ID", '=', $clientID],
            ["CLTR_DATE", '>=', $from],
            ["CLTR_DATE", '<=',  date('Y-m-d', strtotime('+1 day', strtotime($to)))]])
            ->orderBy('id', 'desc')->first()->CLTR_BLNC ?? 0;           

        return $ret;
    }


    static function getTotals($from, $to){

        $ret = array();
        $ret['data'] = DB::table("client_trans")->join('clients', "CLTR_CLNT_ID", "=", "clients.id")
                                            ->select( "clients.CLNT_NAME", "clients.CLNT_BLNC", "clients.id")
                                            ->selectRaw("SUM(CLTR_CASH_AMNT) as totalCash, SUM(CLTR_SALS_AMNT) as totalPurch, 
                                                         SUM(CLTR_DISC_AMNT) as totalDisc, SUM(CLTR_RTRN_AMNT) as totalReturn, SUM(CLTR_NTPY_AMNT) as totalNotes")
                            ->where([
                                ["CLTR_DATE", '>=', $from],
                                ["CLTR_DATE", '<=',  date('Y-m-d', strtotime('+1 day', strtotime($to)))]
                            ])->groupBy("CLTR_CLNT_ID")->get();

        $ret['totals'] = DB::table("client_trans")->join('clients', "CLTR_CLNT_ID", "=", "clients.id")
                            ->selectRaw("SUM(CLTR_CASH_AMNT) as totalCash, SUM(CLTR_SALS_AMNT) as totalPurch, SUM(DISTINCT clients.CLNT_BLNC) as totalBalance, 
                                        SUM(CLTR_DISC_AMNT) as totalDisc, SUM(CLTR_RTRN_AMNT) as totalReturn, SUM(CLTR_NTPY_AMNT) as totalNotes")
                            ->where([
                                ["CLTR_DATE", '>=', $from],
                                ["CLTR_DATE", '<=',  date('Y-m-d', strtotime('+1 day', strtotime($to)))]
                            ])->get()->first();  
        
        return $ret;
    }

    ///////////////////Transactions//////////////////////
    static function getTrans($clientID=null){
        $query = DB::table("client_trans")->join('clients', "CLTR_CLNT_ID", "=", "clients.id")
                                    ->select("client_trans.*", "clients.CLNT_NAME", "clients.CLNT_ARBC_NAME");
        if($clientID !== null){
            $query = $query->where("CLTR_CLNT_ID", $clientID);
            return $query->orderBy('id', 'asc')->limit(500)->get();
        } else {
            return $query->orderBy('id', 'desc')->limit(500)->get(); 
        }
        
    }

    static function getLastTransaction($clientID){
        return DB::table("client_trans")->where("CLTR_CLNT_ID", $clientID)->orderBy('id', 'desc')->first();
    }

    static function insertTrans($client, $sales, $cash, $notespay, $discount, $return, $comment, $desc=null){
        
        $clientLastTrans  = self::getLastTransaction($client);
        if($clientLastTrans !== null) {
            
            $cashBalance    = $clientLastTrans->CLTR_CASH_BLNC;
            $discBalance    = $clientLastTrans->CLTR_DISC_BLNC;
            $notesBalance   = $clientLastTrans->CLTR_NTPY_BLNC;
            $salsBalance    = $clientLastTrans->CLTR_SALS_BLNC;
            $returnBalance  = $clientLastTrans->CLTR_RTRN_BLNC;
            $oldBalance     = $clientLastTrans->CLTR_BLNC;
        } else {
            $cashBalance    = 0;
            $discBalance    = 0;
            $notesBalance   = 0;
            $salsBalance    = 0;
            $returnBalance  = 0;
            $oldBalance     = self::getClientBalance($client);
        }

        DB::transaction(function () use ($client, $sales, $cash, $notespay, $discount, $return, $comment,
                                        $cashBalance, $discBalance, $salsBalance, $notesBalance, $returnBalance, $oldBalance, $desc)
                {

                    $newBalance     =   $oldBalance + $sales - $cash - $notespay - $return - $discount;

                    $id = DB::table("client_trans")->insertGetId([
                        "CLTR_CLNT_ID"      => $client,
                        "CLTR_SALS_AMNT"    => (double) $sales,
                        "CLTR_SALS_BLNC"    => (double) $salsBalance + $sales,
                        "CLTR_CASH_AMNT"    => (double) $cash,
                        "CLTR_CASH_BLNC"    => (double) $cashBalance + $cash,
                        "CLTR_DISC_AMNT"    => (double) $discount,
                        "CLTR_DISC_BLNC"    => (double) $discBalance + $discount,
                        "CLTR_NTPY_AMNT"    => (double) $notespay,
                        "CLTR_NTPY_BLNC"    => (double) $notesBalance + $notespay,
                        "CLTR_RTRN_AMNT"    => (double) $return,
                        "CLTR_RTRN_BLNC"    => (double) $returnBalance + $return,
                        "CLTR_BLNC"         => $newBalance,
                        "CLTR_Date"         => date("Y-m-d H:i:s"),
                        "CLTR_CMNT"         =>  $comment,
                        "CLTR_DESC"         =>  $desc
                    ]);

                    if($cash > 0){
                        $clientDetails = Clients::getClient($client);
                        Cash::insertTran("Client ({$clientDetails->CLNT_NAME}) TRN.# " . $id , $cash, 0, $comment);
                    }
                    if($notespay > 0){
                        $clientDetails = Clients::getClient($client);
                        Bank::insertTran("Client ({$clientDetails->CLNT_NAME}) TRN.# " . $id , $notespay, 0, $comment);
                    }
                    

                    self::updateBalance($client, $newBalance);
        });
    }
    
    
    
    static function correctFaultyTran($id){
        $faulty = self::getOneRecord($id);
        if($faulty==null || $faulty->CLTR_EROR!=0) return 0;
        try {
            $exception = DB::transaction(function () use ($id, $faulty) {
                self::markTranError($id);
                //self::insertTran("Error Correction for TR#" . $id, $faulty->CLTR_IN*-1, $faulty->CLTR_OUT*-1, "Automated Transaction to correct Transaction number " . $id, 2);
            });
            return 1;
        } catch (Exception $e){
            return 0;
        }
        
    }

    static function unmarkTranError($id){
        $faulty = self::getOneRecord($id);
        if($faulty==null || $faulty->CLTR_EROR==0) return 0;
        return DB::table("client_trans")->where('id', $id)->update([
            "CLTR_EROR" => 0
        ]); 
    }

    static private function getOneRecord($id){
        return DB::table('client_trans')->find($id);
    }

    static private function markTranError($id){
       return DB::table("client_trans")->where('id', $id)->update([
           "CLTR_EROR" => 1
       ]);
    }
     
    
    
    /////////////////////////////////////////Clients Table CRUD////////////////////
    static function getClients(){
        return DB::table('clients')->select('clients.*')
                                        ->get();
    }

    static function getClient($id){
        return DB::table('clients')->select('clients.*')
                                        ->where('clients.id', $id)
                                        ->first();
    }

    static function insert($name, $arbcName, $balance, $address=null, $tele=null, $comment=null){
        return DB::table('clients')->insertGetId([
            "CLNT_NAME" => $name,
            "CLNT_ARBC_NAME" => $arbcName,
            "CLNT_ADRS"      => $address,
            "CLNT_TELE"      => $tele,
            "CLNT_CMNT"      => $comment,
            "CLNT_BLNC" =>  $balance
        ]);
    }
    
    static function updateClient($id, $name, $arbcName, $balance, $address=null, $tele=null, $comment=null){
        
        return DB::table('clients')->where('id', $id)->update([
            "CLNT_NAME"         => $name,
            "CLNT_ARBC_NAME"    => $arbcName,
            "CLNT_ADRS"      => $address,
            "CLNT_TELE"      => $tele,
            "CLNT_CMNT"      => $comment,
            "CLNT_BLNC"         => $balance
        ]);
    }

    static function updateBalance($clientID, $balance){
        return DB::table('clients')->where('id', $clientID)->update([
            "CLNT_BLNC"         => $balance
        ]);
    }

    static function getClientBalance($clientID){
        return DB::table('clients')->where('id', $clientID)->select("CLNT_BLNC")->first()->CLNT_BLNC;
    }

    static function getTotalBalance(){
        return DB::table('clients')->sum('CLNT_BLNC');
    }


}
