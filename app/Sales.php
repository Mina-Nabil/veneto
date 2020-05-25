<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

use App\Clients;

use App\Cash;
use App\Bank;

class Sales extends Model
{
    //
    public static function getSales($online = -1)
    {
        $query = DB::table("sales")->join('clients', 'SALS_CLNT_ID', '=', 'clients.id')
            ->select("sales.*", "clients.CLNT_NAME");
        if ($online == 0)
            $query = $query->where("SALS_ONLN", 0);
        elseif ($online == 1)
            $query = $query->where("SALS_ONLN", 1);

        return       $query->orderBy('sales.id', 'desc')->limit(500)->get();
    }

    public static function getAllSoldItems()
    {
        $ret = array();
        $ret['data'] = DB::table("sales_items")
            ->join('sales', 'SLIT_SALS_ID', '=', 'sales.id')
            ->join("finished", "SLIT_FNSH_ID", '=', 'finished.id')
            ->join("models", "FNSH_MODL_ID", '=', 'models.id')
            ->join("types", "MODL_TYPS_ID", '=', 'types.id')
            ->join("raw", "TYPS_RAW_ID", '=', 'raw.id')
            ->join("brands", "FNSH_BRND_ID", '=', "brands.id")
            ->select(
                "sales.*",
                "brands.BRND_NAME",
                "models.MODL_UNID",
                "models.MODL_IMGE",
                "types.TYPS_NAME",
                "raw.RAW_NAME",
                DB::raw(" (SUM(FNSH_36_SOLD)) as sold36"),
                DB::raw(" (SUM(FNSH_38_SOLD)) as sold38"),
                DB::raw(" (SUM(FNSH_40_SOLD)) as sold40"),
                DB::raw(" (SUM(FNSH_42_SOLD)) as sold42"),
                DB::raw(" (SUM(FNSH_44_SOLD)) as sold44"),
                DB::raw(" (SUM(FNSH_46_SOLD)) as sold46"),
                DB::raw(" (SUM(FNSH_48_SOLD)) as sold48"),
                DB::raw(" (SUM(FNSH_50_SOLD)) as sold50"),
                DB::raw(" ( (SUM(FNSH_36_SOLD)) + (SUM(FNSH_38_SOLD)) + (SUM(FNSH_40_SOLD)) + (SUM(FNSH_42_SOLD)) + (SUM(FNSH_44_SOLD)) + (SUM(FNSH_46_SOLD)) + (SUM(FNSH_48_SOLD)) + (SUM(FNSH_50_SOLD)) ) as itemsCount")
            )
            ->groupBy('finished.id')
            ->get();

        $ret['totals'] = DB::table("sales_items")
            ->join('sales', 'SLIT_SALS_ID', '=', 'sales.id')
            ->join("finished", "SLIT_FNSH_ID", '=', 'finished.id')
            ->join("models", "FNSH_MODL_ID", '=', 'models.id')
            ->join("types", "MODL_TYPS_ID", '=', 'types.id')
            ->join("raw", "TYPS_RAW_ID", '=', 'raw.id')
            ->join("brands", "FNSH_BRND_ID", '=', "brands.id")
            ->select(
                "sales.*",
                "brands.BRND_NAME",
                "models.MODL_UNID",
                "models.MODL_IMGE",
                "types.TYPS_NAME",
                "raw.RAW_NAME",
                DB::raw(" (SUM(FNSH_36_SOLD)) as sold36"),
                DB::raw(" (SUM(FNSH_38_SOLD)) as sold38"),
                DB::raw(" (SUM(FNSH_40_SOLD)) as sold40"),
                DB::raw(" (SUM(FNSH_42_SOLD)) as sold42"),
                DB::raw(" (SUM(FNSH_44_SOLD)) as sold44"),
                DB::raw(" (SUM(FNSH_46_SOLD)) as sold46"),
                DB::raw(" (SUM(FNSH_48_SOLD)) as sold48"),
                DB::raw(" (SUM(FNSH_50_SOLD)) as sold50"),
                DB::raw(" ( (SUM(FNSH_36_SOLD)) + (SUM(FNSH_38_SOLD)) + (SUM(FNSH_40_SOLD)) + (SUM(FNSH_42_SOLD)) + (SUM(FNSH_44_SOLD)) + (SUM(FNSH_46_SOLD)) + (SUM(FNSH_48_SOLD)) + (SUM(FNSH_50_SOLD)) ) as itemsCount")
            )
            ->get()->first();

        return $ret;
    }

    public static function getSalesByClient($clientID)
    {
        return DB::table("sales")->join('clients', 'SALS_CLNT_ID', '=', 'clients.id')
            ->select("sales.*", "clients.CLNT_NAME")
            ->where("SALS_CLNT_ID", $clientID)->get();
    }

    public static function getSalesItemsByClient($clientID)
    {
        return DB::table("sales_items")
            ->join('sales', 'SLIT_SALS_ID', '=', 'sales.id')
            ->join("finished", "SLIT_FNSH_ID", '=', 'finished.id')
            ->join("models", "FNSH_MODL_ID", '=', 'models.id')
            ->join("types", "MODL_TYPS_ID", '=', 'types.id')
            ->join("raw", "TYPS_RAW_ID", '=', 'raw.id')
            ->join("brands", "FNSH_BRND_ID", '=', "brands.id")
            ->select(
                "sales.*",
                "brands.BRND_NAME",
                "models.MODL_UNID",
                "models.MODL_IMGE",
                "types.TYPS_NAME",
                "raw.RAW_NAME",
                DB::raw(" (SUM(FNSH_36_SOLD)) as sold36"),
                DB::raw(" (SUM(FNSH_38_SOLD)) as sold38"),
                DB::raw(" (SUM(FNSH_40_SOLD)) as sold40"),
                DB::raw(" (SUM(FNSH_42_SOLD)) as sold42"),
                DB::raw(" (SUM(FNSH_44_SOLD)) as sold44"),
                DB::raw(" (SUM(FNSH_46_SOLD)) as sold46"),
                DB::raw(" (SUM(FNSH_48_SOLD)) as sold48"),
                DB::raw(" (SUM(FNSH_50_SOLD)) as sold50"),
                DB::raw(" ( (SUM(FNSH_36_SOLD)) + (SUM(FNSH_38_SOLD)) + (SUM(FNSH_40_SOLD)) + (SUM(FNSH_42_SOLD)) + (SUM(FNSH_44_SOLD)) + (SUM(FNSH_46_SOLD)) + (SUM(FNSH_48_SOLD)) + (SUM(FNSH_50_SOLD)) ) as itemsCount")
            )
            ->groupBy('finished.id')
            ->where("SALS_CLNT_ID", $clientID)->get();
    }

    public static function getSalesTotalsByClient($clientID)
    {
        return DB::table("sales")
            ->selectRaw("SUM(SALS_TOTL_PRCE) as totalPrice, SUM(SALS_PAID) as totalPaid")
            ->where("SALS_CLNT_ID", $clientID)->get()->first();
    }

    public static function getOneSalesOp($id)
    {
        $retArr = array();
        $retArr['sales'] = DB::table("sales")->join('clients', 'SALS_CLNT_ID', '=', 'clients.id')
            ->select("sales.*", "clients.CLNT_NAME", "clients.CLNT_ADRS", "clients.CLNT_TELE")
            ->selectRaw("DATE_FORMAT(sales.SALS_DATE, '%Y-%m-%d') as formatedDate")
            ->where("sales.id", $id)->get()->first();

        $retArr['items']   = DB::table("sales_items")
            ->join("finished", "SLIT_FNSH_ID", '=', 'finished.id')
            ->join("models", "FNSH_MODL_ID", '=', 'models.id')
            ->join("types", "MODL_TYPS_ID", '=', 'types.id')
            ->join("raw", "TYPS_RAW_ID", '=', 'raw.id')
            ->join("brands", "FNSH_BRND_ID", '=', "brands.id")
            ->select(
                "sales_items.*",
                "brands.BRND_NAME",
                "models.MODL_UNID",
                "models.MODL_IMGE",
                "types.TYPS_NAME",
                "raw.RAW_NAME",
                DB::raw(" (FNSH_36_SOLD + FNSH_38_SOLD + FNSH_40_SOLD + FNSH_42_SOLD + FNSH_44_SOLD + FNSH_46_SOLD + FNSH_48_SOLD + FNSH_50_SOLD) as itemsCount")
            )
            ->where("SLIT_SALS_ID", $id)
            ->get();


        $retArr['totalNum']  = DB::table("sales_items")
            ->select(DB::raw("SUM(FNSH_36_SOLD + FNSH_38_SOLD + FNSH_40_SOLD + FNSH_42_SOLD + FNSH_44_SOLD + FNSH_46_SOLD + FNSH_48_SOLD +  FNSH_50_SOLD) as totalNum"))
            ->where("SLIT_SALS_ID", $id)
            ->get()
            ->first()->totalNum;
        return $retArr;
    }

    public static function insertSales($clientID, $itemsArr, $total, $paid = 0, $isbank = false, $comment = null, $isOnline=0)
    {
        DB::transaction(function () use ($clientID, $itemsArr, $total, $paid, $isbank, $comment, $isOnline) {

            $id = DB::table("sales")->insertGetId([
                "SALS_DATE" => date("Y-m-d H:i:s"),
                "SALS_CLNT_ID" => $clientID,
                "SALS_CMNT"     => $comment,
                "SALS_PAID"     => $paid,
                "SALS_ONLN"     => $isOnline,
                "SALS_TOTL_PRCE" => $total
            ]);

            Clients::insertTrans($clientID, $total, $paid, 0, 0, 0, "Sales Comment: $comment", "Sales " . $id);

            if ($paid > 0)
                if ($isbank) {
                    Bank::insertTran("Sales " . $id, $paid, 0, "Initial Payment for Sales " . $id . " Sales comment: " . $comment);
                } else {
                    Cash::insertTran("Sales " . $id, $paid, 0, "Initial Payment for Sales " . $id . " Sales comment: " . $comment);
                }

            foreach ($itemsArr as $item) {
                DB::table("sales_items")->insert([
                    "SLIT_FNSH_ID" => $item['finished'],
                    "SLIT_SALS_ID" => $id,
                    "SLIT_PRCE" => $item['price'],
                    "FNSH_36_SOLD" => ($item['amount36']) ? $item['amount36'] : 0,
                    "FNSH_38_SOLD" => ($item['amount38']) ? $item['amount38'] : 0,
                    "FNSH_40_SOLD" => ($item['amount40']) ? $item['amount40'] : 0,
                    "FNSH_42_SOLD" => ($item['amount42']) ? $item['amount42'] : 0,
                    "FNSH_44_SOLD" => ($item['amount44']) ? $item['amount44'] : 0,
                    "FNSH_46_SOLD" => ($item['amount46']) ? $item['amount46'] : 0,
                    "FNSH_48_SOLD" => ($item['amount48']) ? $item['amount48'] : 0,
                    "FNSH_50_SOLD" => ($item['amount50']) ? $item['amount50'] : 0
                ]);
            }

            Finished::insertSoldEntry($itemsArr);
        });
    }

    public static function insertPayment($sales, $payment, $isbank)
    {
        DB::transaction(function () use ($sales, $payment, $isbank) {

            DB::table("sales")->where('id', $sales)->increment("SALS_PAID", $payment);
            $clientID = self::getClientID($sales);
            if ($isbank)
                Clients::insertTrans($clientID, 0, $payment, 0, 0, 0, "Payment added for Specific Sales Operation " . $sales, "Sales " . $sales);
            else
                Clients::insertTrans($clientID, 0, 0, $payment, 0, 0, "Payment added for Specific Sales Operation " . $sales, "Sales " . $sales);
        });
    }

    private static function getClientID($salesID)
    {
        return DB::table("sales")->find($salesID)->SALS_CLNT_ID;
    }
}
