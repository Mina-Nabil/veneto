<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Brands;
use App\TransType;

class TransTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    function showTransType(){
        $data['transTypes'] = TransType::getTransTypes();
        $data['transSubTypes'] = TransType::getTransSubTypes();
        $data['pageTitle'] = "Manage Transaction Types";
        $data['subFormURL'] = url("transsubtype/insert");
        $data['formURL'] = url("transtype/insert");
        return view("cash.transtype", $data);
    }


    function editTransSubType($id){
        $data['transTypes'] = TransType::getTransTypes();
        $data['transSubTypes'] = TransType::getTransSubTypes();
        $data['transSubType']  = TransType::getTransSubType($id);
        $data['pageTitle'] = "Edit " . $data['transSubType']->TRST_NAME ;
        $data['formURL'] = url('transtype/insert');
        $data['subFormURL'] = url('transsubtype/update');
        return view("cash.transtype", $data);
    }

    function updateTransSubType(Request $request){
        
        $validate = $request->validate([
            "name" => "required",
            "typeID" => "required",
            "id"    => "required"
        ]);
        
        TransType::updateTransSubType($request->id, $request->name, $request->typeID);

        return \redirect("transtype/show");

    }

    function insertTransSubType(Request $request){
        $validate = $request->validate([
            "name" => "required",
            "typeID" => "required",
            ]);
        
            TransType::insertTransSubType($request->typeID, $request->name);

        return \redirect("transtype/show");
    }

    ////////////////////////Transtypes controller/////////////////////////////////

    function editTransType($id){
        $data['transTypes'] = TransType::getTransTypes();
        $data['transSubTypes'] = TransType::getTransSubTypes();
        $data['transType']  = TransType::getTransType($id);
        $data['pageTitle'] = "Edit " . $data['transType']->TRTP_NAME ;
        $data['formURL'] = url('transtype/update');
        $data['subFormURL'] = url("transsubtype/insert");
        return view("cash.transtype", $data);
    }

    function updateTransType(Request $request){
        
        $validate = $request->validate([
            "name" => "required",
            "id"    => "required"
        ]);
        
        TransType::updateTransType($request->id, $request->name);

        return \redirect("transtype/show");

    }

    function insertTransType(Request $request){
        $validate = $request->validate([
            "name" => "required"
            ]);
        
            TransType::insertTransType($request->name);

        return \redirect("transtype/show");
    }
}
