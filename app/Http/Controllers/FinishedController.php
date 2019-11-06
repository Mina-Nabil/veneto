<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Finished;
use App\Brands;
use App\Models;

class FinishedController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(){
        $data['finished'] = Finished::getAvailable();
        return view("finished.home", $data);
    }

    public function addPage(){

        $data['models'] = Models::getModels();
        $data['brands'] = Brands::getBrands();

        $data['pageTitle'] = "New Finished Entry";
        $data['pageDescription']  = "Add new finished entry";
        $data['formURL'] = url("finished/insert");

        return view('finished.add', $data);
    }

    public function insert(Request $request){
        Finished::insertFinishedEntry($this->getItemsArr($request));
        return redirect("finished/show");
    }

    public function editPrice(Request $request){
        Finished::updatePrice($request->id, $request->price);
        return back();
    }

    private function getItemsArr($request){
        $ret = array();
        foreach($request->model as $key => $item){
            array_push($ret, [
                "model"     => $item,
                "brand"     => $request->brand[$key],
                "amount36"     => ($request->amount36[$key])?$request->amount36[$key] : 0,
                "amount38"     => ($request->amount38[$key])?$request->amount38[$key] : 0,
                "amount40"     => ($request->amount40[$key])?$request->amount40[$key] : 0,
                "amount42"     => ($request->amount42[$key])?$request->amount42[$key] : 0,
                "amount44"     => ($request->amount44[$key])?$request->amount44[$key] : 0,
                "amount46"     => ($request->amount46[$key])?$request->amount46[$key] : 0,
                "amount48"     => ($request->amount48[$key])?$request->amount48[$key] : 0,
                "amount50"     => ($request->amount50[$key])?$request->amount50[$key] : 0,
                "price"     => $request->price[$key],
            ]);
        }
        return $ret;
    }
}
