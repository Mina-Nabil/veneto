<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bank;
use App\TransType;
use Validator;

class BankController extends Controller
{
    function __construct(){
        $this->middleware('auth'); 
    }

    ////////////////Reports//////////////
    function reportPage(){
        return view("bank.report", ['reportFormURL' => url("bank/report")]);
    }

    function report(Request $request){
        $data['ops'] = Bank::getReport($request->from, $request->to);
        $data['report'] = true;
        return view("bank.home", $data);
    }



    ///////////////Transactions///////////
    function show(){
        $data['ops'] = Bank::getTrans();
        $data['report'] = false;
        return view("bank.home", $data);
    }

    function addPage(){

        $data['pageTitle'] = "New Bank Operation";
        $data['pageDescription'] = "Add New Bank Transaction";
        $data['formURL'] = url("bank/insert");

        $data['transSubTypes'] = TransType::getTransSubTypes();

        return view("bank.add", $data);
    }

    function insert(Request $request){

        $validatedDate = $request->validate([
            "name" => "required",
            'in'    => "required",
            'out'   => "required",
        ]);
        
        Bank::insertTran($request->name, $request->in, $request->out, $request->comment, 0, $request->typeID);

        return \redirect("bank/show");
    }

    function markError(Request $request){

        $validator = Validator::make($request->all(), [
            'tranId' => 'required'
        ]);

        if ($validator->fails())
            echo 0;
        else
            echo Bank::correctFaultyTran($request->tranId);

        return;

    }

    function unmarkError(Request $request){

        $validator = Validator::make($request->all(), [
            'tranId' => 'required'
        ]);

        if ($validator->fails())
            echo 0;
        else
            echo Bank::unmarkTranError($request->tranId);

        return;

    }
}
