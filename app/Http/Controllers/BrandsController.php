<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Brands;

class BrandsController extends Controller
{
    //
    function showBrand(){
        $data['brands'] = Brands::getBrands();
        $data['pageTitle'] = "Add New Brand";
        $data['formURL'] = url("brands/insert");
        return view("finished.brands", $data);
    }

    function editBrand($id){
        $data['brands'] = Brands::getBrands();
        $data['brand']  = Brands::getBrand($id);
        $data['pageTitle'] = "Edit " . $data['brand']->BRND_NAME ;
        $data['formURL'] = url('brands/update');
        return view("finished.brands", $data);
    }

    function updateBrand(Request $request){
        
        $validate = $request->validate([
            "name" => "required",
            "id"    => "required"
        ]);
        
        Brands::updateBrand($request->id, $request->name, $request->code);

        return \redirect("brands/show");

    }

    function insertBrand(Request $request){
        $validate = $request->validate([
            "name" => "required"
            ]);
        
        Brands::insertBrand($request->name);

        return \redirect("brands/show");
    }
}
