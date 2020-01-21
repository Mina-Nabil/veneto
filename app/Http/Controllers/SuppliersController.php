<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Suppliers;

class SuppliersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    ////////Supplier Transactions/////////////
    function quickReport($id=null){

        $data['ops'] = Suppliers::getTrans($id);
        $data['isSupplier'] = ($id != null);
        if($data['isSupplier']){
            $data['totals'] = Suppliers::getLastTransaction($id);
            $data['supplier'] = Suppliers::getSupplier($id);
        }
        return view("suppliers.report", $data);
    }

    //prepare report page
    function report(){
        
        $data['suppliers'] = Suppliers::getSuppliers();

        $data['accountStatFormURL'] = url('suppliers/account/statement');
        $data['mainReportFormURL'] = url('suppliers/main/account');

        return view('suppliers.prepare', $data);
    }
    
    function accountStatement(Request $request){

        $data['arr'] = Suppliers::getAccountStatement($request->supplier, $request->from, $request->to);
        $data['supplier'] = Suppliers::getSupplier($request->supplier);

        if($data['supplier'] == null) return abort(404);

        $data['totals'] = $data['arr']['totals'];
        $data['ops']    = $data['arr']['trans'];
        $data['balance']    = $data['arr']['balance'];

        if($data['totals'] == null){
            $data['totals'] = (object)[
                'totalPurch' => 0,
                'totalDisc' => 0,
                'totalReturn' => 0,
                'totalNotes' => 0,
                'totalCash' => 0
            ];
        }

        $data['reportTitle'] = $data['supplier']->SUPP_NAME . " Account Statement";
        $data['reportDesc'] = $data['supplier']->SUPP_NAME . " Current Balance is " . $data['supplier']->SUPP_BLNC;

        $data['startBalance'] = $data['balance'] - $data['totals']->totalPurch + $data['totals']->totalDisc + $data['totals']->totalCash +
                                $data['totals']->totalReturn + $data['totals']->totalNotes ;

        return view('suppliers.accnt_stat', $data);

    }

    function mainReport(Request $request){
        $data['ops'] = Suppliers::getTotals($request->from, $request->to);
        return view('suppliers.main_report', $data);
    }

    function addTransPage(){

        $data['suppliers']  = Suppliers::getSuppliers();

        $data['pageTitle']  = "Add Supplier Operation";
        $data['pageDescription']    = "Add New Supplier Operation";
        $data['formURL']            = url("suppliers/trans/insert");

        return view('suppliers.add_trans', $data);
    }
    
    function insertTrans(Request $request){

        Suppliers::insertTrans( $request->supplier, $request->purchase, $request->cash, 
                                $request->notes, $request->disc, $request->return, $request->comment, $request->desc);
        
        return redirect("suppliers/trans/quick");

    }
    
    ///////////Supplier Pages///////////////////
    function home(){
        $data['suppliers']  = Suppliers::getSuppliers();
        $data['total']      = Suppliers::getTotalBalance(); 
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

        Suppliers::insert($request->name, $request->arbcName, $request->type, $request->balance, $request->address, $request->tele, $request->comment);

        return redirect("suppliers/show");
    }

    function edit($id){

        $data['types']      =   Suppliers::getTypes();
        $data['supplier']   =   Suppliers::getSupplier($id);

        if($data['supplier'] == null )abort(404);

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

        Suppliers::updateSupplier($request->id, $request->name, $request->arbcName, $request->type, $request->balance, $request->address, $request->tele, $request->comment);

        return redirect("suppliers/show");
    }



    //////////////////////////Supplier Types Function/////////////////////////
    function types(){
        $data['types'] = Suppliers::getTypes();

        $data['pageTitle'] = "Supplier Type";
        $data['pageDescription'] = "Add New Supplier Type";
        $data['formURL'] = url("suppliers/types/insert");

        return view('suppliers.types', $data);
    }

    function insertType(Request $request){

        $validatedData = $request->validate([
            "name"  => "required"
        ]);

        Suppliers::insertType($request->name);
        
        return redirect("suppliers/types/show"); 
    }

    function editType($id){

        $data['types'] = Suppliers::getTypes();

        $data['type'] = Suppliers::getType($id);
        $data['pageTitle'] = "Supplier Type";
        $data['pageDescription'] = "Editing Supplier Type: " . $data['type']->SPTP_NAME;
        $data['formURL'] = url("suppliers/types/update");

        return view('suppliers.types', $data);
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
