<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

use App\Cash;
use App\Bank;
use DateTime;

class Clients extends Model
{
    ///////////////////////Reports/////////////////////
    static function getAccountStatement($clientID, $from, $to)
    {
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
            ["CLTR_DATE", '<=',  date('Y-m-d', strtotime('+1 day', strtotime($to)))]
        ])
            ->orderBy('id', 'desc')->first()->CLTR_BLNC ?? 0;

        return $ret;
    }


    static function getTotals($from, $to, $isOnline = -1)
    {

        $ret = array();
        $from = (new DateTime($from))->format('Y-m-d H:i:s');
        $to = ((new DateTime($to))->setTime(23, 59, 59))->format('Y-m-d H:i:s');

        $ret['data'] = DB::table("clients")->join('client_trans', "CLTR_CLNT_ID", "=", "clients.id")
            ->select("clients.CLNT_NAME", "clients.id")
            ->selectRaw("SUM(CLTR_CASH_AMNT) as totalCash, SUM(CLTR_SALS_AMNT) as totalPurch,
                                                         SUM(CLTR_DISC_AMNT) as totalDisc, SUM(CLTR_RTRN_AMNT) as totalReturn, SUM(CLTR_NTPY_AMNT) as totalNotes")
            ->where([
                ["CLTR_DATE", ">=", $from],
                ["CLTR_DATE", "<=", $to],
            ]);

        if ($isOnline == 0) {
            $ret['data'] = $ret['data']->where("CLNT_ONLN", 0);
        } elseif ($isOnline == 1) {
            $ret['data'] = $ret['data']->where("CLNT_ONLN", 1);
        } elseif ($isOnline == 2) {
            $ret['data'] = $ret['data']->where("CLNT_ONLN", 2);
        }
        $ret['data'] = $ret['data']->groupBy("CLTR_CLNT_ID")->orderBy("CLNT_NAME")->get();

        if ($isOnline != -1)
            $balances = DB::table("client_trans as t1")->selectRaw("t1.id, CLTR_CLNT_ID , CLTR_BLNC , CLTR_DATE")
                ->join("clients", "clients.id", '=', 't1.CLTR_CLNT_ID')
                ->havingRaw("t1.id = (SELECT max(id) from client_trans WHERE CLNT_ONLN={$isOnline} AND t1.CLTR_CLNT_ID = CLTR_CLNT_ID AND  CLTR_DATE >= '{$from}' AND CLTR_DATE <= '{$to}' ) ")
                ->get();
        else
            $balances = DB::table("client_trans as t1")->selectRaw("id, CLTR_CLNT_ID , CLTR_BLNC , CLTR_DATE")
                ->havingRaw("id = (SELECT max(id) from client_trans WHERE t1.CLTR_CLNT_ID = CLTR_CLNT_ID AND  CLTR_DATE >= '{$from}' AND CLTR_DATE <= '{$to}' ) ")
                ->get();


        $ret['balances'] = [];
        $ret['totalBalance'] = 0;
        foreach ($balances as $balance) {
            $ret['balances'][$balance->CLTR_CLNT_ID] = $balance->CLTR_BLNC;
            $ret['totalBalance'] += $balance->CLTR_BLNC;
        }


        $ret['others'] = DB::table("clients as t1")->join('client_trans', "CLTR_CLNT_ID", "=", "t1.id")
            ->select(['t1.id', 'CLTR_BLNC', 'CLNT_NAME'])
            ->whereNotIn('t1.id', $balances->pluck('CLTR_CLNT_ID'))
            ->whereRaw(" client_trans.id = (SELECT MAX(id) FROM client_trans WHERE CLTR_CLNT_ID = t1.id AND CLTR_DATE <= '{$to}' ) ")->orderBy("CLNT_NAME");

        $ret['onlineOthers'] = [];

        if ($isOnline == -1) {
            $ret['others'] = $ret['others']->get();
        } else {
            $ret['others'] = $ret['others']->where("CLNT_ONLN", $isOnline)->get();
        }


        $ret['totals'] = DB::table("clients")->join('client_trans', "CLTR_CLNT_ID", "=", "clients.id")
            ->selectRaw("SUM(CLTR_CASH_AMNT) as totalCash, SUM(CLTR_SALS_AMNT) as totalPurch,
                                        SUM(CLTR_DISC_AMNT) as totalDisc, SUM(CLTR_RTRN_AMNT) as totalReturn, SUM(CLTR_NTPY_AMNT) as totalNotes")
            ->where([
                ["CLTR_DATE", ">=", $from],
                ["CLTR_DATE", "<=", $to],
            ]);

        $ret['onlineTotals'] = [];
        if ($isOnline == 0) {
            $ret['totals'] = $ret['totals']->where("CLNT_ONLN", 0);
        } elseif ($isOnline == 1) {
            $ret['onlineTotals'] = $ret['totals']->where("CLNT_ONLN", 1)->get()->first();
        } elseif ($isOnline == 2) {
            $ret['onlineTotals'] = $ret['totals']->where("CLNT_ONLN", 2)->get()->first();
        }
        $ret['totals'] = $ret['totals']->get()->first();

        foreach ($ret['others'] as $mloshTrans) {
            $ret['totalBalance'] += $mloshTrans->CLTR_BLNC;
        }

        return $ret;
    }

    static function getFullTotals($from, $to)
    {
        $from = (new DateTime($from))->format('Y-m-d H:i:s');
        $to = ((new DateTime($to))->setTime(23, 59, 59))->format('Y-m-d H:i:s');
   
        $balances = DB::table("client_trans as t1")->selectRaw("t1.id, CLTR_CLNT_ID , CLTR_BLNC , CLTR_DATE")
            ->join("clients", "clients.id", '=', 't1.CLTR_CLNT_ID')
            ->havingRaw("t1.id = (SELECT max(id) from client_trans WHERE t1.CLTR_CLNT_ID = CLTR_CLNT_ID AND  CLTR_DATE >= '{$from}' AND CLTR_DATE <= '{$to}' ) ")
            ->get();

            $ret['totals'] = DB::table("clients")->join('client_trans', "CLTR_CLNT_ID", "=", "clients.id")
            ->selectRaw("SUM(CLTR_CASH_AMNT) as totalCash, SUM(CLTR_SALS_AMNT) as totalPurch,
                                        SUM(CLTR_DISC_AMNT) as totalDisc, SUM(CLTR_RTRN_AMNT) as totalReturn, SUM(CLTR_NTPY_AMNT) as totalNotes")
            ->where([
                ["CLTR_DATE", ">=", $from],
                ["CLTR_DATE", "<=", $to],
            ])
            ->get()->first();


        $ret['others'] = DB::table("clients as t1")->join('client_trans', "CLTR_CLNT_ID", "=", "t1.id")
            ->select(['t1.id', 'CLTR_BLNC', 'CLNT_NAME'])
            ->whereNotIn('t1.id', $balances->pluck('CLTR_CLNT_ID'))
            ->whereRaw(" client_trans.id = (SELECT MAX(id) FROM client_trans WHERE CLTR_CLNT_ID = t1.id AND CLTR_DATE <= '{$to}' ) ")->get();

        $ret['totalBalance'] = 0;
        foreach ($balances as $balance) {
            $ret['totalBalance'] += $balance->CLTR_BLNC;
        }

        foreach ($ret['others'] as $mloshTrans) {
            $ret['totalBalance'] += $mloshTrans->CLTR_BLNC;
        }
    
        return $ret;
    }

    ///////////////////Transactions//////////////////////
    static function getTrans($clientID = null)
    {
        $query = DB::table("client_trans")->join('clients', "CLTR_CLNT_ID", "=", "clients.id")
            ->select("client_trans.*", "clients.CLNT_NAME", "clients.CLNT_ARBC_NAME");
        if ($clientID !== null) {
            $query = $query->where("CLTR_CLNT_ID", $clientID);
            return $query->orderBy('id', 'asc')->limit(500)->get();
        } else {
            return $query->orderBy('id', 'desc')->limit(500)->get();
        }
    }

    static function getLastTransaction($clientID)
    {
        return DB::table("client_trans")->where("CLTR_CLNT_ID", $clientID)->orderBy('id', 'desc')->first();
    }

    static function insertTrans($client, $sales, $cash, $notespay, $discount, $return, $comment, $desc = null)
    {

        $clientLastTrans  = self::getLastTransaction($client);
        if ($clientLastTrans !== null) {

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

        DB::transaction(function () use (
            $client,
            $sales,
            $cash,
            $notespay,
            $discount,
            $return,
            $comment,
            $cashBalance,
            $discBalance,
            $salsBalance,
            $notesBalance,
            $returnBalance,
            $oldBalance,
            $desc
        ) {

            $newBalance     =   $oldBalance + $sales - $cash - $notespay - $return - $discount;

            $id = DB::table("client_trans")->insertGetId([
                "CLTR_CLNT_ID"      => $client,
                "CLTR_SALS_AMNT"    => (float) $sales,
                "CLTR_SALS_BLNC"    => (float) $salsBalance + $sales,
                "CLTR_CASH_AMNT"    => (float) $cash,
                "CLTR_CASH_BLNC"    => (float) $cashBalance + $cash,
                "CLTR_DISC_AMNT"    => (float) $discount,
                "CLTR_DISC_BLNC"    => (float) $discBalance + $discount,
                "CLTR_NTPY_AMNT"    => (float) $notespay,
                "CLTR_NTPY_BLNC"    => (float) $notesBalance + $notespay,
                "CLTR_RTRN_AMNT"    => (float) $return,
                "CLTR_RTRN_BLNC"    => (float) $returnBalance + $return,
                "CLTR_BLNC"         => $newBalance,
                "CLTR_Date"         => date("Y-m-d H:i:s"),
                "CLTR_CMNT"         =>  $comment,
                "CLTR_DESC"         =>  $desc
            ]);

            if ($cash != 0) {
                $clientDetails = Clients::getClient($client);
                Cash::insertTran("Client ({$clientDetails->CLNT_NAME}) TRN.# " . $id, $cash, 0, $comment);
            }
            if ($notespay != 0) {
                $clientDetails = Clients::getClient($client);
                Bank::insertTran("Client ({$clientDetails->CLNT_NAME}) TRN.# " . $id, $notespay, 0, $comment);
            }


            self::updateBalance($client, $newBalance);
        });
    }



    static function correctFaultyTran($id)
    {
        $faulty = self::getOneRecord($id);
        if ($faulty == null || $faulty->CLTR_EROR != 0) return 0;
        try {
            $exception = DB::transaction(function () use ($id, $faulty) {
                self::markTranError($id);
                //self::insertTran("Error Correction for TR#" . $id, $faulty->CLTR_IN*-1, $faulty->CLTR_OUT*-1, "Automated Transaction to correct Transaction number " . $id, 2);
            });
            return 1;
        } catch (Exception $e) {
            return 0;
        }
    }

    static function unmarkTranError($id)
    {
        $faulty = self::getOneRecord($id);
        if ($faulty == null || $faulty->CLTR_EROR == 0) return 0;
        return DB::table("client_trans")->where('id', $id)->update([
            "CLTR_EROR" => 0
        ]);
    }

    static private function getOneRecord($id)
    {
        return DB::table('client_trans')->find($id);
    }

    static private function markTranError($id)
    {
        return DB::table("client_trans")->where('id', $id)->update([
            "CLTR_EROR" => 1
        ]);
    }



    /////////////////////////////////////////Clients Table CRUD////////////////////
    static function getClients()
    {
        return DB::table('clients')->select('clients.*')->orderBy("CLNT_NAME")
            ->get();
    }

    static function getClient($id)
    {
        return DB::table('clients')->select('clients.*')
            ->where('clients.id', $id)
            ->first();
    }

    static function insert($name, $arbcName, $balance, $address = null, $tele = null, $comment = null, $isOnline = 0)
    {
        return DB::table('clients')->insertGetId([
            "CLNT_NAME" => $name,
            "CLNT_ARBC_NAME" => $arbcName,
            "CLNT_ADRS"      => $address,
            "CLNT_TELE"      => $tele,
            "CLNT_CMNT"      => $comment,
            "CLNT_ONLN"      => $isOnline,
            "CLNT_BLNC" =>  $balance
        ]);
    }

    static function updateClient($id, $name, $arbcName, $balance, $address = null, $tele = null, $comment = null, $isOnline = 0)
    {

        return DB::table('clients')->where('id', $id)->update([
            "CLNT_NAME"         => $name,
            "CLNT_ARBC_NAME"    => $arbcName,
            "CLNT_ADRS"      => $address,
            "CLNT_TELE"      => $tele,
            "CLNT_CMNT"      => $comment,
            "CLNT_ONLN"      => $isOnline,
            "CLNT_BLNC"         => $balance
        ]);
    }

    static function updateBalance($clientID, $balance)
    {
        return DB::table('clients')->where('id', $clientID)->update([
            "CLNT_BLNC"         => $balance
        ]);
    }

    static function getClientBalance($clientID)
    {
        return DB::table('clients')->where('id', $clientID)->select("CLNT_BLNC")->first()->CLNT_BLNC;
    }

    static function getTotalBalance()
    {
        return DB::table('clients')->sum('CLNT_BLNC');
    }
}
