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

    public function showModelRolls($rawID, $typeID, $suppID){

        $data['transPage'] =  true;
        $data['raws'] = RawInventory::getRollsByGroup( $rawID, $suppID, $typeID);

        $data['isProd'] = false;
        return view('rawInventory.rolls', $data);
    }

    public function showFullTransaction($tran){


        $data['raws'] = RawInventory::getRollsByTran($tran);
        $data['transPage'] =  true;
        if(!isset($data['raws'][0]))return abort(404);
        $data['model'] = $data['raws'][0];

        if(strcmp($data['model']->MODL_UNID, '') != 0 ){
            $data['pageDesc'] = "for Transaction: "  . $tran;
        }
        $data['isProd'] = false;
        return view('rawInventory.rolls', $data);
    }

    public function addPage(Request $request){

        $data['pageTitle'] = "Add New Entry";
        $data['pageDescription'] = "Note: This operation can't be reverted!";
        $data['formURL'] = url("rawinventory/insert");
        $data['addNewURL'] = url("rawinventory/addentry");

        $data['totals']  =   $request->session()->get('totals', array(
            "totalPrice"    => 0,
            "meter"         => 0,
            "numberOfInv"   => 0
        ));

        $data['entries']    =   $request->session()->get('entry', array());
        $data['model']    =   $request->session()->get('model', null);

        $data['types'] = Models::getTypes();
        $data['colors']  = Models::getColors();
        $data['suppliers']  = Suppliers::getSuppliers();
   
        return view('rawInventory.add', $data);
    }

    public function setEntry(Request $request){ 
        
        $validatedData = $request->validate([
            "supplier" => "required",
            "type" => "required",
            "color" => "required",
            "name" => "required",
            "price" => "required",
        ]);      
        
        $this->addNewEntry($request);
        $this->setModel($request);
        return redirect('rawinventory/add');
    }

    public function cancelEntry(Request $request){

        $this->cancelEntries($request);

        return redirect("rawinventory/show");
    }

    public function transactions(){
        $data['trans'] = RawInventory::getTransactions();
        return view('rawInventory.tran', $data);
    }

    public function insert(Request $request){

        $validatedData = $request->validate([
            "supplier" => "required",
            "type" => "required",
            "color" => "required",
            "name" => "required",
            "price" => "required",
        ]);

        $entries = $this->addNewEntry($request);
        $totals  =   $request->session()->get('totals', array(
            "totalPrice"    => 0,
            "meter"         => 0,
            "numberOfInv"   => 0
        ));
        $tranNumber = date("YmdHis");

        RawInventory::insertEntry($entries, $totals, $tranNumber);
        $this->cancelEntries($request);

        return \redirect("rawinventory/show");

    }

/////////////////////////////Production Functions///////////////////////////////////

    public function showProduction(){

        $data['raws'] = RawInventory::getProduction();
        $data['isProd'] = true;
        $data['transPage'] =  true;
        return view('rawInventory.rolls', $data);

    }

    // public function addProd(){

    //     $data['raws'] =       RawInventory::getAvailable();
    //     $data['pageTitle'] = "Add to Production";
    //     $data['pageDescription'] = "Add to Production Lines";
    //     $data['formURL'] = url("raw/prod/insert");

    //     return view('rawInventory.addprod', $data); 
    // }

    public function insertProdFull($id){

        if(!isset($id) || !is_numeric($id))
            abort(404);

        RawInventory::incrementProduction($id);

        return redirect('raw/prod/show');
    }

    public function insertProd(Request $request){
        $validatedDate = $request->validate([
            "raw" => "required",
            "in"    => "required"
        ]);

        RawInventory::incrementProduction($request->raw, $request->in);

        return redirect('raw/prod/show');
    }

    public function insertFromProd(Request $request){
        $validatedDate = $request->validate([
            "raw" => "required",
            "in"    => "required",
            "toRaw" => "required"
        ]);

        RawInventory::decrementProduction($request->raw, $request->in, $request->toRaw);

        return redirect('raw/prod/show');
    }

/////////////////////////////Transaction Functions/////////////////////////////////

    public function addTran(){

        $data['raws'] =       RawInventory::getFullInventory();
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

    /////////////////////////Private Functions//////////////////////////////////
    private function addNewEntry($request){

        $path = null;
        if ($request->hasFile('photo')) {
            $path = $request->photo->store('images/models', 'public');
        }

        $oldArray = $request->session()->get('entry', array());

        $typeArr = explode('%', $request->type);
        $colorArr = explode('%', $request->color);
     
        $newArr = array(
           "name" => $request->name,
           "type" => $typeArr[0],
           "typeName" => $typeArr[1],
           "color" => $colorArr[0],
           "colorName" => $colorArr[1],
           "supplier" => $request->supplier,
           "price"  =>$request->price,
           "photo"  => $path,
           "serial" => $request->serial,
           "comment" => $request->comment 
        );

        for($i=0 ; isset($request->amount[$i]) ; $i++){
            $newArr["items"][$i]     =   $request->amount[$i];
        }

        array_push($oldArray, $newArr);

        $request->session()->put("entry", $oldArray);
        $this->setTotals($request);
        return $oldArray;
    }

    private function setTotals($request){

        $totalsArr = array(
            "totalPrice"    => 0,
            "meter"         => 0,
            "numberOfInv"   => 0
        );

        $entryArr = $request->session()->get("entry", array());

        foreach($entryArr as $row){
            $totalsArr['numberOfInv'] += count($row['items']);
            foreach ($row['items'] as $item) {
                $totalsArr['meter'] += $item;
                $totalsArr['totalPrice'] += $row['price'] * $item;
            }
        }

        $request->session()->put("totals", $totalsArr);

    }

    private function setModel($request){
        $request->session()->put("model", array([
            "MODL_SUPP_ID"  => $request->supplier,
            "MODL_COLR_ID"  => $request->color,
            "MODL_TYPS_ID"  => $request->type,
            "MODL_NAME"     => $request->name,
            "MODL_PRCE"     => $request->price,
            "MODL_CMNT"     => $request->comment,
            "MODL_UNID"     => $request->serial
        ]));
    }

    private function cancelEntries($request){
        $entries = $request->session()->get("entry", array());
        foreach($entries as $entry)
            if($entry['photo'] !== null && file_exists( 'storage/' . $entry['photo']))
                unlink( 'storage/' . $entry['photo'] );
        $request->session()->forget("entry");
        $request->session()->forget("totals");
        $request->session()->forget("model");
    }
    
}
