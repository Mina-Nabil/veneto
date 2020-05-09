<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cash;
use App\TransType;

use Validator;

class CashController extends Controller
{
    function __construct(){
        $this->middleware('auth'); 
    }

    ////////////////Reports//////////////
    function reportPage(){
        return view("cash.report", ["reportFormURL" => url("cash/report")]);
    }

    function report(Request $request){
        $data['ops'] = Cash::getReport($request->from, $request->to);
        $data['report'] = true;
        return view("cash.home", $data);
    }

    ///////////////Transactions///////////
    function show($subtypeID=0){
        $data['ops'] = Cash::getTrans($subtypeID);
        $data['report'] = false;
        return view("cash.home", $data);
    }

    function addPage(){

        $data['pageTitle'] = "New Cash Operation";
        $data['pageDescription'] = "Add New Cash Transaction";
        $data['formURL'] = url("cash/insert");

        $data['transSubTypes'] = TransType::getTransSubTypes();

        return view("cash.add", $data);
    }

    function insert(Request $request){

        $validatedDate = $request->validate([
            "name" => "required",
            'in'    => "required",
            'out'   => "required",
        ]);
        
        Cash::insertTran($request->name, $request->in, $request->out, $request->comment, 0, $request->typeID);

        return \redirect("cash/show");
    }

    function markError(Request $request){

        $validator = Validator::make($request->all(), [
            'tranId' => 'required'
        ]);

        if ($validator->fails())
            echo 0;
        else
            echo Cash::correctFaultyTran($request->tranId);

        return;

    }

    function unmarkError(Request $request){

        $validator = Validator::make($request->all(), [
            'tranId' => 'required'
        ]);

        if ($validator->fails())
            echo 0;
        else
            echo Cash::unmarkTranError($request->tranId);

        return;

    }

}
