<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cash;

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
        return view("cash.home", $data);
    }

    ///////////////Transactions///////////
    function show(){
        $data['ops'] = Cash::getTrans();
        return view("cash.home", $data);
    }

    function addPage(){

        $data['pageTitle'] = "New Cash Operation";
        $data['pageDescription'] = "Add New Cash Transaction";
        $data['formURL'] = url("cash/insert");

        return view("cash.add", $data);
    }

    function insert(Request $request){

        $validatedDate = $request->validate([
            "name" => "required",
            'in'    => "required",
            'out'   => "required",
        ]);
        
        Cash::insertTran($request->name, $request->in, $request->out, $request->comment);

        return \redirect("cash/show");
    }


}
