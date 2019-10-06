<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Suppliers;

class SuppliersController extends Controller
{
    //
    function home(){
        $data['suppliers'] = Suppliers::getSuppliers();
        return view('suppliers.home', $data);
    }

    function addPage(){
        
        $data['types']      =   Suppliers::getTypes();

        $data['pageTitle'] = "New Supplier";
        $data['pageDescription'] = "Add New Supplier - Data Required: Name - Type - Balance";
        $data['formURL']        =   url("suppliers/insert");

        return view("suppliers.add", $data);
    }

    function insert(Request $request){
        $validatedData = $request->validate([
            "name"  => "required",
            "type"  => "required",
            "balance" => "required"
        ]);

        Suppliers::insert($request->name, $request->arbcName, $request->type, $request->balance);

        return redirect("suppliers/show");
    }

    function edit($id){

        $data['types']      =   Suppliers::getTypes();
        $data['supplier']   =   Suppliers::getSupplier($id);

        $data['pageTitle'] = "Supplier: " . $data['supplier']->SUPP_NAME;
        $data['pageDescription'] = "Edit ("  . $data['supplier']->SUPP_NAME .  ") - Data Required: Name - Type - Balance";
        $data['formURL']        =   url("suppliers/update");

        return view("suppliers.add", $data);  

    }

    function updateSupplier(Request $request){
        $validatedData = $request->validate([
            "id"    => "required",
            "name"  => "required",
            "type"  => "required",
            "balance" => "required"
        ]);

        Suppliers::updateSupplier($request->id, $request->name, $request->arbcName, $request->type, $request->balance);

        return redirect("suppliers/show");
    }



    //////////////////////////Supplier Types Function/////////////////////////
    function types(){
        $data['types'] = Suppliers::getTypes();

        return view('suppliers.types', $data);
    }

    function addType(){

        $data['pageTitle'] = "Supplier Type";
        $data['pageDescription'] = "Add New Supplier Type";
        $data['formURL'] = url("suppliers/types/insert");

        return view('suppliers.addType', $data);
    }

    function insertType(Request $request){

        $validatedData = $request->validate([
            "name"  => "required"
        ]);

        Suppliers::insertType($request->name);
        
        return redirect("suppliers/types/show"); 
    }

    function editType($id){

        $data['type'] = Suppliers::getType($id);
        $data['pageTitle'] = "Supplier Type";
        $data['pageDescription'] = "Editing Supplier Type: " . $data['type']->SPTP_NAME;
        $data['formURL'] = url("suppliers/types/update");

        return view('suppliers.addType', $data);
    }

    function update(Request $request){

        $validatedData = $request->validate([
            "id" => "required",
            "name"  => "required"
        ]);
        Suppliers::editType($request->id, $request->name);
        
        return redirect("suppliers/types/show");
    }
}
