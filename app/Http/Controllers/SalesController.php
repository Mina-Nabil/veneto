<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Clients;
use App\Sales;
use App\Finished;

use convert_ar ;

class SalesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show($clientID = null){

        if($clientID) {
            $data['client'] = Clients::getClient($clientID);

            if($data['client']==null) 
                abort(404);
            
            $data['sales'] = Sales::getSalesByClient($clientID);
            $data['items'] = Sales::getSalesItemsByClient($clientID);
            $data['totals'] = Sales::getSalesTotalsByClient($clientID);
            $data['isClntPage'] = true;
        } else {
            $data['sales'] = Sales::getSales(0);
            $data['onlineSales'] = Sales::getSales(1);
            $data['viaSales'] = Sales::getSales(2);
            $data['prodSales'] = Sales::getSales(3);
            $data['procSales'] = Sales::getSales(4);
            $data['isClntPage'] = false;

        }

        return view("sales.home", $data);

    }

    public function sales($salesID){
        $data = Sales::getOneSalesOp($salesID);
        return view("sales.operation", $data);
    }

    public function invoice($salesID){
        $data = Sales::getOneSalesOp($salesID);
        $numberStr = number_format($data['sales']->SALS_TOTL_PRCE, 2);
        $numArr = explode('.', $numberStr);
        $decimal = str_replace(",", "", $numArr[1]);
        $wholeNum = str_replace(",", "", $numArr[0]);
        $wholeConverter = new convert_ar($wholeNum, "male");
        $decimalConverter = new convert_ar($decimal, "male");

        $data['totalInArabic'] = $wholeConverter->convert_number() . " جنيها مصريا ";
        
        if($decimal != "00") {
            $data['totalInArabic'] .= " و " . $decimalConverter->convert_number() . " قرشا فقط لا غير" ;
        } else {
            $data['totalInArabic'] .= " فقط لا غير " ;
        }
        
        return view("sales.invoice", $data);
    }

    public function addPage(){

        $data['items'] = Finished::getAllFinished();
        $data['clients'] = Clients::getClients();

        $data['pageTitle'] = "Add Sales";
        $data['pageDescription'] = "Add New Sales Operation";
        $data['formURL'] = url("sales/insert");
        $data['isReturn'] = false;

        return view("sales.add", $data);
    }

    public function insert(Request $request){
        $itemsArr = $this->getItemsArray($request);
        $isOnline = ($request->isOnline == "on") ? 1 : 0;
        Sales::insertSales($request->clientID, $itemsArr['items'], $itemsArr['totalPrice'], $request->paid, $request->type, $request->comment, $isOnline);
        return redirect("sales/show");
    }

    public function insertPayment(Request $request){

        Sales::insertPayment($request->salesID, $request->payment, $request->type);
        return back(); 

    }

    public function allItemsSold(){
        $data['items'] = Sales::getAllSoldItems();
        return view("sales.sold", $data);
    }

    ///////////////?Return Pages//////////////////////


    public function addReturnPage(){

        $data['items'] = Finished::getAllFinished();
        $data['clients'] = Clients::getClients();

        $data['pageTitle'] = "Add New Return";
        $data['pageDescription'] = "Add New Return Operation";
        $data['formURL'] = url("sales/return/insert");
        $data['isReturn'] = true;

        return view("sales.add", $data);
    }

    public function insertReturn(Request $request){
        $itemsArr = $this->getItemsArray($request);
        Sales::insertReturn($request->clientID, $itemsArr['items'], $itemsArr['totalPrice'], $request->comment);
        return redirect("sales/show");
    }


    private function getItemsArray($request){

        $ret = array();
        $ret['items'] = array();
        $ret['totalPrice'] = 0;

        foreach($request->amount36 as $key=>$item){
            array_push($ret['items'], [
                "finished" => $request->finished[$key],
                "amount36" => $request->amount36[$key],
                "amount38" => $request->amount38[$key],
                "amount40" => $request->amount40[$key],
                "amount42" => $request->amount42[$key],
                "amount44" => $request->amount44[$key],
                "amount46" => $request->amount46[$key],
                "amount48" => $request->amount48[$key],
                "amount50" => $request->amount50[$key],
                "price" => $request->price[$key]
            ]);

            $ret['totalPrice'] += ($request->amount36[$key] 
                                  + $request->amount38[$key]
                                  + $request->amount40[$key]
                                  + $request->amount42[$key]
                                  + $request->amount44[$key]
                                  + $request->amount46[$key]
                                  + $request->amount48[$key]
                                  + $request->amount50[$key]
                                ) * $request->price[$key] ;
        }

        return $ret;
    }
}
