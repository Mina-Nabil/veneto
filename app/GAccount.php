<?php

namespace App;

use DateTime;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GAccount extends Model
{
    public static function getAllGeneralAccounts()
    {
        return DB::table('gen_accounts')->join('gen_accounts_title', 'gen_accounts_title.id', '=', 'GNAC_GNTL_ID')
            ->select("gen_accounts.*", "gen_accounts_title.GNTL_NAME")
            ->orderBy("GNTL_SORT")
            ->get();
    }

    public static function getAccount($id)
    {
        return DB::table('gen_accounts')->find($id);
    }

    public static function getAccountBalance($id)
    {
        $account = DB::table('gen_accounts')->findOrFail($id);
        if (isset($account->GNAC_BLNC))
            return $account->GNAC_BLNC;
        else return 0;
    }

    public static function insertAccount($titleID, $nature, $name, $balance, $arabicName = null, $desc = null)
    {
        $id = DB::table('gen_accounts')->insertGetId([
            "GNAC_GNTL_ID" => $titleID,
            "GNAC_NATR" =>  $nature,
            "GNAC_NAME" =>  $name,
            "GNAC_BLNC" =>  $balance ?? 0,
            "GNAC_ARBC_NAME"    =>  $arabicName,
            "GNAC_DESC" =>  $desc
        ]);
        if ($balance != 0) {
            $credit = 0;
            $debit = 0;
            if ($nature == 1) {
                if ($balance > 0)
                    $credit = $balance;
                else
                    $debit = -1*$balance;
            } else if($nature == 2){
                if ($balance > 0)
                    $debit = $balance;
                else
                    $credit = -1*$balance;
            }
            DB::table("gen_accounts_trans")->insert([
                "GNTR_GNAC_ID" => $id,
                "GNTR_DEBT" =>  $debit,
                "GNTR_CRDT" =>  $credit,
                "GNTR_DATE" => (new DateTime())->format("Y-m-d H:i:s"),
                "GNTR_GNAC_BLNC" => $balance,
                "GNTR_TTLE" =>  "Initial Balance",
                "GNTR_CMNT" =>  "Added Automatically by system"
            ]);
        }
    }

    public static function editAccountInfo($id, $titleID, $name, $arabicName = null, $desc = null)
    {
        return DB::table('gen_accounts')->where("id", $id)->update([
            "GNAC_GNTL_ID" => $titleID,
            "GNAC_NAME" =>  $name,
            "GNAC_ARBC_NAME"    =>  $arabicName,
            "GNAC_DESC" =>  $desc
        ]);
    }

    private static function setAccountBalance($id, $newBalance)
    {
        DB::table('gen_accounts')->where("id", $id)->update([
            "GNAC_BLNC" => $newBalance
        ]);
    }

    public static function deleteAccount($id)
    {
        try {
            DB::table("gen_accounts_trans")->where("GNTR_GNAC_ID", $id)->delete();
            DB::table("gen_accounts")->where("id", $id)->delete();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    ////////////account transactions
    public static function getAccountTransactions($accountID, $limit = null, $from = null, $to = null)
    {
        $query = DB::table("gen_accounts_trans")->where("GNTR_GNAC_ID", $accountID);
        if ($from == null && $to == null) {
            $query = $query->whereYear("GNTR_DATE", "=", (new DateTime()));
        } else {
            if ($from !== null) {
                $query = $query->whereDate("GNTR_DATE", ">=", $from);
            }
            if ($to !== null) {
                $query = $query->whereDate("GNTR_DATE", "<=", $to);
            }
        }

        if ($limit !== null) {
            $query = $query->limit($limit);
        }
        return $query->where("GNTR_GNAC_ID", $accountID)->limit($limit)->get();
    }


    public static function addTransaction($accountID, $title, $credit = 0, $debit = 0, $comment = null, $isCash = false)
    {
        if ($credit != 0  || $debit != 0) {
            try {
                DB::transaction(function () use ($accountID, $title, $credit, $debit, $comment, $isCash) {
                    $account = self::getAccount($accountID);
                    $oldBalance = $account->GNAC_BLNC ?? 0;
                    if ($account->GNAC_NATR == 1) { //debit nature
                        $newBalance = $oldBalance + $debit - $credit;
                    } else { //credit nature
                        $newBalance = $oldBalance - $debit +  $credit;
                    }
                    DB::table("gen_accounts_trans")->insert([
                        "GNTR_GNAC_ID" => $accountID,
                        "GNTR_DEBT" =>  $debit,
                        "GNTR_CRDT" =>  $credit,
                        "GNTR_DATE" => (new DateTime())->format("Y-m-d H:i:s"),
                        "GNTR_GNAC_BLNC" => $newBalance,
                        "GNTR_TTLE" =>  $title,
                        "GNTR_CMNT" =>  $comment
                    ]);
                    self::setAccountBalance($accountID, $newBalance);
                    if($isCash){
                        Cash::insertTran("General Account - " . self::getAccountName($accountID) . " : (" . $title . ")", $debit, $credit, $comment, 0, null);
                    }
                });
                return true;
            } catch (Exception $e) {
                return false;
            }
        }
    }


    ////////////account titles (fixed - current..)
    public static function getAccountTitles()
    {
        return DB::table('gen_accounts_title')->get();
    }

    public static function getAccountsByTitle($titleID)
    {
        return DB::table('gen_accounts')->where("GNAC_GNTL_ID", $titleID)->get();
    }

    public static function setAccountTitleSort($accountTitleID, $sortValue)
    {
        return DB::table('gen_accounts_title')->where("id", $accountTitleID)->update([
            "GNTL_SORT" => $sortValue
        ]);
    }

    public static function addAccountTitle($accountTitleName, $sortValue)
    {
        return DB::table('gen_accounts_title')->insert([
            "GNTL_NAME" => $accountTitleName,
            "GNTL_SORT" => $sortValue,
        ]);
    }

    public static function deleteAccountTitle($id, bool $force = false)
    {
        $accounts = self::getAccountsByTitle($id);
        if ($force || count($accounts) == 0) {
            return DB::table('gen_accounts_title')->where('id', $id)->delete();
        }
    }

    public static function getAccountName($id) {
        $res = DB::table('gen_accounts')->where("id", $id)->first();
        if($res) {
            return $res->GNAC_NAME ?? null;
        }
        return null;

    }
}
