<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Target extends Model
{
    protected $table = "targets";
    public $timestamps = false;

    public static function getTargets($year, $month)
    {
        $currMonth = new DateTime($year . '-' . $month . '-01');
        $startDate = $currMonth->format('Y-m-01 H:i:s');
        $endDate = $currMonth->format('Y-m-t H:i:s');

        $clientTrans = DB::table('client_trans')->where([
            ["CLTR_DATE", ">=", $startDate],
            ["CLTR_DATE", "<=", $endDate],
        ]);

        return DB::table('targets')
            ->join('clients', 'TRGT_CLNT_ID', '=', 'clients.id')
            ->leftJoinSub($clientTrans, 'client_trans', 'CLTR_CLNT_ID', '=', 'clients.id')
            ->select('targets.*', 'CLNT_NAME', 'CLNT_SRNO', 'CLNT_BLNC')
            ->selectRaw('SUM(CLTR_CASH_AMNT) as cashPaid, SUM(CLTR_NTPY_AMNT) as bankPaid')
            ->orderBy('CLNT_ONLN')->orderBy('CLNT_SRNO')
            ->where([['TRGT_YEAR', $year], ['TRGT_MNTH', $month]])
            ->groupBy('targets.id', 'clients.id')

            ->get();
    }

    public static function getTargetTotals($year, $month)
    {
        $currMonth = new DateTime($year . '-' . $month . '-01');
        $startDate = $currMonth->format('Y-m-01 H:i:s');
        $endDate = $currMonth->format('Y-m-t H:i:s');

        $clientTrans = DB::table('client_trans')->where([
            ["CLTR_DATE", ">=", $startDate],
            ["CLTR_DATE", "<=", $endDate],
        ]);

        return DB::table('targets')
            ->join('clients', 'TRGT_CLNT_ID', '=', 'clients.id')
            ->leftJoinSub($clientTrans, 'client_trans', 'CLTR_CLNT_ID', '=', 'clients.id')
            ->selectRaw('SUM(CLNT_BLNC) as balanceTotal, SUM(CLTR_CASH_AMNT) as cashPaid, SUM(CLTR_NTPY_AMNT) as bankPaid, SUM(TRGT_MONY) as cashTarget, SUM(TRGT_BANK) as bankTarget')
            ->where([['TRGT_YEAR', '=',$year], ['TRGT_MNTH', '=',$month]])->toSql();
           
    }

    public static function createYearlyTargets($year)
    {
        $clients = Clients::getClients();
        foreach ($clients as $client) {
            for ($i = 1; $i < 13; $i++) {
                DB::table('targets')->insert([
                    'TRGT_CLNT_ID'  => $client->id,
                    'TRGT_MONY'     => 0,
                    'TRGT_BANK'     => 0,
                    'TRGT_MNTH'     => $i,
                    'TRGT_YEAR'     => $year
                ]);
            }
        }
    }

    public static function isTargetSet($client, $month, $year)
    {
        $targetCount = DB::table('targets')->where("TRGT_CLNT_ID", $client)->where("TRGT_MNTH", $month)->where('TRGT_YEAR', $year)->get()->count();
        echo "<br>ClientID : " . $client . " is " . $targetCount;
        return ($targetCount > 0) ? true : false;
    }

    public static function generateTargets($year)
    {
        $clients = Clients::getClients();
        foreach ($clients as $client) {
            for ($i = 1; $i < 13; $i++) {
                if (!self::isTargetSet($client->id, $i, $year))
                    DB::table('targets')->insert([
                        'TRGT_CLNT_ID'  => $client->id,
                        'TRGT_MONY'     => 0,
                        'TRGT_BANK'     => 0,
                        'TRGT_MNTH'     => $i,
                        'TRGT_YEAR'     => $year
                    ]);
            }
        }
    }
}
