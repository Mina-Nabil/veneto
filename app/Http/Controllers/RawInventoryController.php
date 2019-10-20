<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models;
use App\Suppliers;
use App\RawInventory;

class RawInventoryController extends Controller
{
    public function showAvailable(){
        $data['raws'] = RawInventory::getAvailable();
        $data['isProd'] = false;
        return view('rawInventory.home', $data);
    }

    public function addPage(){

        $data['models'] =       Models::getModelList();
        $data['suppliers'] =    Suppliers::getSuppliers();
        $data['pageTitle'] = "Add New Raw Inventory Item";
        $data['pageDescription'] = "Note: This operation can't be reverted!";
        $data['formURL'] = url("rawinventory/insert");

        return view('rawInventory.add', $data);
    }

    public function transactions(){
        $data['trans'] = RawInventory::getTransactions();
        return view('rawInventory.tran', $data);
    }

    public function insert(Request $request){
        $validatedData = $request->validate([
            "supplier" => "required",
            "model" => "required",
            "price" => "required",
            "amount" => "required",
        ]);
        
        RawInventory::insert($request->model, $request->supplier, $request->amount, $request->price, $request->discount);

        return \redirect("rawinventory/show");

    }

/////////////////////////////Production Functions///////////////////////////////////

    public function showProduction(){
    $data['raws'] = RawInventory::getProduction();
    $data['isProd'] = true;
    return view('rawInventory.home', $data);
    }

    public function addProd(){

        $data['raws'] =       RawInventory::getAvailable();
        $data['pageTitle'] = "Add to Production";
        $data['pageDescription'] = "Add to Production Lines";
        $data['formURL'] = url("raw/prod/insert");

        return view('rawInventory.addprod', $data); 
    }

    public function insertProd(Request $request){
        $validatedDate = $request->validate([
            "raw" => "required",
            "in"    => "required"
        ]);

        RawInventory::incrementProduction($request->raw, $request->in);

        return redirect('raw/prod/show');
    }


/////////////////////////////Transaction Functions/////////////////////////////////

    public function addTran(){

        $data['raws'] =       RawInventory::getAvailable();
        $data['pageTitle'] = "Add New Transaction for Raw Inventory";
        $data['pageDescription'] = "Note: This operation can't be reverted!";
        $data['formURL'] = url("raw/tran/insert");

        return view('rawInventory.addtran', $data); 
    }

    public function insertTran(Request $request){
        $validatedData = $request->validate([
            "raw" => "required",
            "in" => "required",
            "out" => "required",
            "from" => "required",
            "to" => "required",
        ]);
        
        RawInventory::insertTransaction($request->raw, $request->in, $request->out, true, $request->from, $request->to);

        return \redirect("rawinventory/tran");

    }

    
}
