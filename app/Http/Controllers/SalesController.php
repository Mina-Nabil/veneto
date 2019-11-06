<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Clients;
use App\Sales;
use App\Finished;

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
            $data['isClntPage'] = true;
        } else {
            $data['sales'] = Sales::getSales($clientID);
            $data['isClntPage'] = false;

        }

        return view("sales.home", $data);

    }

    public function sales($salesID){
        $data = Sales::getOneSalesOp($salesID);
        return view("sales.operation", $data);
    }

    public function addPage(){

        $data['items'] = Finished::getAvailable();
        $data['clients'] = Clients::getClients();

        $data['pageTitle'] = "Add Sales";
        $data['pageDescription'] = "Add New Sales Operation";
        $data['formURL'] = url("sales/insert");

        return view("sales.add", $data);
    }

    public function insert(Request $request){
        $itemsArr = $this->getItemsArray($request);
        Sales::insertSales($request->clientID, $itemsArr['items'], $itemsArr['totalPrice'], $request->paid, $request->type, $request->comment);
        return redirect("sales/show");
    }

    public function insertPayment(Request $request){

        Sales::insertPayment($request->salesID, $request->payment, $request->type);
        return back(); 

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
