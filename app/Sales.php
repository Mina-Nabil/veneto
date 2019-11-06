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
    public static function getSales(){
        return DB::table("sales")->join('clients', 'SALS_CLNT_ID', '=', 'clients.id')
                        ->select("sales.*", "clients.CLNT_NAME")
                        ->orderBy('sales.id', 'desc')->limit(500)->get();
    }

    public static function getSalesByClient($clientID){
        return DB::table("sales")->join('clients', 'SALS_CLNT_ID', '=', 'clients.id')
                    ->select("sales.*", "clients.CLNT_NAME")
                    ->where("SALS_CLNT_ID", $clientID)->get();
    }

    public static function getOneSalesOp($id){
        $retArr = array();
        $retArr ['sales'] = DB::table("sales")->join('clients', 'SALS_CLNT_ID', '=', 'clients.id')
                                ->select("sales.*", "clients.CLNT_NAME")
                                ->where("sales.id", $id)->get()->first();

        $retArr['items']   = DB::table("sales_items")
                                    ->join("finished", "SLIT_FNSH_ID", '=', 'finished.id')
                                    ->join("models", "FNSH_MODL_ID", '=', 'models.id')
                                    ->join("types", "MODL_TYPS_ID", '=', 'types.id')
                                    ->join("raw", "TYPS_RAW_ID", '=', 'raw.id')
                                    ->join("brands", "FNSH_BRND_ID", '=', "brands.id") 
                                    ->select("sales_items.*", "brands.BRND_NAME", "models.MODL_UNID", "models.MODL_IMGE", "types.TYPS_NAME", "raw.RAW_NAME" ,
                                            DB::raw(" (FNSH_36_SOLD + FNSH_38_SOLD + FNSH_40_SOLD + FNSH_42_SOLD + FNSH_44_SOLD + FNSH_46_SOLD + FNSH_48_SOLD + FNSH_50_SOLD) as itemsCount")) 
                                    ->where("SLIT_SALS_ID", $id)
                                    ->get();

        
        $retArr['totalNum']  = DB::table("sales_items")->select(DB::raw("SUM(FNSH_36_SOLD + FNSH_38_SOLD + FNSH_40_SOLD + FNSH_42_SOLD 
                                                                + FNSH_44_SOLD + FNSH_46_SOLD + FNSH_48_SOLD +  FNSH_50_SOLD) as totalNum"))
                                                        ->where("SLIT_SALS_ID", $id)
                                                        ->get()
                                                        ->first()->totalNum;
        return $retArr;
                         
    }

    public static function insertSales($clientID, $itemsArr, $total, $paid=0, $isbank=false, $comment=null){
        DB::transaction( function () use ($clientID, $itemsArr, $total, $paid, $isbank, $comment) {

            $id = DB::table("sales")->insertGetId([
                "SALS_DATE" => date("Y-m-d H:i:s"),
                "SALS_CLNT_ID" => $clientID,
                "SALS_CMNT"     => $comment,
                "SALS_PAID"     => $paid,
                "SALS_TOTL_PRCE" => $total
            ]);

            Clients::insertTrans($clientID, $total, $paid, 0, 0, 0, "Sales " . $id);

           if($paid > 0)
             if($isbank){
                Bank::insertTran("Sales " . $id, $paid, 0, "Initial Payment for Sales " . $id);
            } else {
                Cash::insertTran("Sales " . $id, $paid, 0, "Initial Payment for Sales " . $id);
            }

            foreach($itemsArr as $item){
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

    public static function insertPayment($sales, $payment, $isbank){
        DB::transaction(function () use ($sales, $payment, $isbank){

            DB::table("sales")->increment("SALS_PAID", $payment);
            $clientID = self::getClientID($sales);
            if($isbank)
                Clients::insertTrans($clientID, 0, $payment, 0, 0, 0, "Payment for Sales " . $sales);
            else
                Clients::insertTrans($clientID, 0, 0, $payment, 0, 0, "Payment for Sales " . $sales);

        });
    }

    private static function getClientID($salesID){
        return DB::table("sales")->find($salesID)->SALS_CLNT_ID;
    }
}
