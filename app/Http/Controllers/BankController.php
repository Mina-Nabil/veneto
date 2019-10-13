<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bank;

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
        return view("bank.home", $data);
    }



    ///////////////Transactions///////////
    function show(){
        $data['ops'] = Bank::getTrans();
        return view("bank.home", $data);
    }

    function addPage(){

        $data['pageTitle'] = "New Bank Operation";
        $data['pageDescription'] = "Add New Bank Transaction";
        $data['formURL'] = url("bank/insert");

        return view("bank.add", $data);
    }

    function insert(Request $request){

        $validatedDate = $request->validate([
            "name" => "required",
            'in'    => "required",
            'out'   => "required",
        ]);
        
        Bank::insertTran($request->name, $request->in, $request->out, $request->comment);

        return \redirect("bank/show");
    }
}
