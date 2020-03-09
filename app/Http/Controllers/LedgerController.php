<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ledger;
use App\TransType;

use Validator;

class LedgerController extends Controller
{
    function __construct(){
        $this->middleware('auth'); 
    }

    ////////////////Reports//////////////
    function reportPage(){
        return view("ledger.report", ["reportFormURL" => url("ledger/report")]);
    }

    function report(Request $request){
        $data['ops'] = Ledger::getReport($request->from, $request->to);
        $data['report'] = true;
        return view("ledger.home", $data);
    }

    ///////////////Transactions///////////
    function show(){
        $data['ops'] = Ledger::getTrans();
        $data['report'] = false;
        return view("ledger.home", $data);
    }

    function addPage(){

        $data['pageTitle'] = "New Ledger Operation";
        $data['pageDescription'] = "Add New Ledger Transaction";
        $data['formURL'] = url("ledger/insert");

        $data['transSubTypes'] = TransType::getTransSubTypes();

        return view("ledger.add", $data);
    }

    function insert(Request $request){

        $validatedDate = $request->validate([
            "name" => "required",
            'in'    => "required",
            'out'   => "required",
        ]);
        
        Ledger::insertTran($request->name, $request->in, $request->out, $request->comment, 0, $request->typeID);

        return \redirect("ledger/show");
    }

    function markError(Request $request){

        $validator = Validator::make($request->all(), [
            'tranId' => 'required'
        ]);

        if ($validator->fails())
            echo 0;
        else
            echo Ledger::correctFaultyTran($request->tranId);

        return;

    }

    function unmarkError(Request $request){

        $validator = Validator::make($request->all(), [
            'tranId' => 'required'
        ]);

        if ($validator->fails())
            echo 0;
        else
            echo Ledger::unmarkTranError($request->tranId);

        return;

    }

}
