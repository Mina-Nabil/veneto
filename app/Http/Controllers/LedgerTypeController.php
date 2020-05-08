<?php

namespace App\Http\Controllers;

use App\LedgerType;
use Illuminate\Http\Request;

class LedgerTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    function showLedgerType(){
        $data['ledgerTypes'] = LedgerType::getLedgerTypes();
        $data['ledgerSubTypes'] = LedgerType::getLedgerSubTypes();
        $data['pageTitle'] = "Manage General Ledger Types";
        $data['subFormURL'] = url("ledger/subtypes/insert");
        $data['formURL'] = url("ledger/types/insert");
        return view("ledger.types", $data);
    }


    function editLedgerSubType($id){
        $data['ledgerTypes'] = LedgerType::getLedgerTypes();
        $data['ledgerSubTypes'] = LedgerType::getLedgerSubTypes();
        $data['ledgerSubType']  = LedgerType::getLedgerSubType($id);
        $data['pageTitle'] = "Edit " . $data['ledgerSubType']->LDST_NAME ;
        $data['formURL'] = url('ledger/types/insert');
        $data['subFormURL'] = url('ledger/subtypes/update');
        return view("ledger.types", $data);
    }

    function updateLedgerSubType(Request $request){
        
        $validate = $request->validate([
            "name" => "required",
            "typeID" => "required",
            "id"    => "required"
        ]);
        
        LedgerType::updateLedgerSubType($request->id, $request->name, $request->typeID);

        return \redirect("ledgertype/show");

    }

    function insertLedgerSubType(Request $request){
        $validate = $request->validate([
            "name" => "required",
            "typeID" => "required",
            ]);
        
            LedgerType::insertLedgerSubType($request->typeID, $request->name);

        return \redirect("ledger/types/show");
    }

    ////////////////////////Ledgertypes controller/////////////////////////////////

    function editLedgerType($id){
        $data['ledgerTypes'] = LedgerType::getLedgerTypes();
        $data['ledgerSubTypes'] = LedgerType::getLedgerSubTypes();
        $data['ledgerType']  = LedgerType::getLedgerType($id);
        $data['pageTitle'] = "Edit " . $data['ledgerType']->LDTP_NAME ;
        $data['formURL'] = url('ledger/types/update');
        $data['subFormURL'] = url("ledger/subtypes/insert");
        return view("ledger.types", $data);
    }

    function updateLedgerType(Request $request){
        
        $validate = $request->validate([
            "name" => "required",
            "id"    => "required"
        ]);
        
        LedgerType::updateLedgerType($request->id, $request->name);

        return \redirect("ledger/types/show");

    }

    function insertLedgerType(Request $request){
        $validate = $request->validate([
            "name" => "required"
            ]);
        
            LedgerType::insertLedgerType($request->name);

        return \redirect("ledger/types/show");
    }
}
