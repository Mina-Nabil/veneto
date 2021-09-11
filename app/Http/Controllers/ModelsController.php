<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models;
use App\Suppliers;

class ModelsController extends Controller

{
    public function __construct()
    {
        $this->middleware('auth');
    }

    ////////////////////////////////////////////Models/////////////////////////////////////////

    function show()
    {

        $data['models']  = Models::getModels(1);

        $data['pageTitle'] = "Add New Model";
        $data['formURL'] = url("models/insert");

        return view("finished.modellat", $data);
    }

    function editModel($id)
    {

        $data['types'] = Models::getTypes();
        $data['colors']  = Models::getColors();
        $data['models']  = Models::getModels();
        $data['suppliers']  = Suppliers::getSuppliers();
        $data['model']  = Models::getModel($id);

        $data['pageTitle'] = "Edit " . $data['model']->RAW_NAME . ' ' . $data['model']->TYPS_NAME . ' ' . $data['model']->MODL_NAME;
        $data['formURL'] = url('models/update');

        return view("finished.modellat", $data);
    }

    function updateModel(Request $request)
    {

        $validate = $request->validate([
            "type" => "required",
            "color" => "required",
            "id"    => "required",
            "supplier" => "required",
            "price"     => "required"
        ]);

        $path = null;
        $oldPath = $request->oldPath;

        if ($request->hasFile('photo')) {
            $path = $request->photo->store('images/models', 'public');
        }

        Models::updateModel($request->id, $request->name, $request->type, $request->color, $request->supplier, $request->price, $path, $request->serial, $request->comment);

        return \redirect("models/show");
    }


    function hideModel($id)
    {

        Models::toggleHidden($id, 1);

        return \redirect("models/show");
    }
    function showModel($id)
    {

        Models::toggleHidden($id, 0);

        return \redirect("models/show");
    }

    ////////////////////////////////////////////Raw Materials/////////////////////////////////
    function showRaw()
    {
        $data['raws'] = Models::getRawMaterials();
        $data['pageTitle'] = "Add New Raw Material";
        $data['formURL'] = url("raw/insert");
        return view("models.raws", $data);
    }

    function editRaw($id)
    {
        $data['raws'] = Models::getRawMaterials();
        $data['raw']  = Models::getRawMaterial($id);
        $data['pageTitle'] = "Edit " . $data['raw']->RAW_NAME;
        $data['formURL'] = url('raw/update');
        return view("models.raws", $data);
    }

    function updateRaw(Request $request)
    {

        $validate = $request->validate([
            "name" => "required",
            "id"    => "required"
        ]);

        Models::updateRawMaterial($request->id, $request->name, $request->code);

        return \redirect("raw/show");
    }

    function insertRaw(Request $request)
    {
        $validate = $request->validate([
            "name" => "required"
        ]);

        Models::insertRawMaterial($request->name);

        return \redirect("raw/show");
    }


    ///////////////////////////////////////////Types/////////////////////////////////////////
    function showTypes()
    {
        $data['types'] = Models::getTypes();
        $data['raws']  = Models::getRawMaterials();
        $data['pageTitle'] = "Add New Type";
        $data['formURL'] = url("types/insert");
        return view("models.types", $data);
    }

    function editType($id)
    {
        $data['types'] = Models::getTypes();
        $data['raws']  = Models::getRawMaterials();
        $data['type']  = Models::getType($id);
        $data['pageTitle'] = "Edit " . $data['type']->TYPS_NAME;
        $data['formURL'] = url('types/update');
        return view("models.types", $data);
    }

    function updateType(Request $request)
    {

        $validate = $request->validate([
            "name" => "required",
            "raw" => "required",
            "id"    => "required"
        ]);

        Models::updateType($request->id, $request->name, $request->raw);

        return \redirect("types/show");
    }

    function insertType(Request $request)
    {
        $validate = $request->validate([
            "name" => "required",
            "raw"  => "required"
        ]);

        Models::insertType($request->name, $request->raw);

        return \redirect("types/show");
    }



    /////////////////////////////////////////Colors//////////////////////////////////////////
    function showColors()
    {
        $data['colors'] = Models::getColors();
        $data['pageTitle'] = "Add New Color";
        $data['formURL'] = url("colors/insert");
        return view("models.colors", $data);
    }

    function editColor($id)
    {
        $data['colors'] = Models::getColors();
        $data['color']  = Models::getColor($id);
        $data['pageTitle'] = "Edit " . $data['color']->COLR_NAME . " Color";
        $data['formURL'] = url('colors/update');
        return view("models.colors", $data);
    }

    function updateColor(Request $request)
    {

        $validate = $request->validate([
            "name" => "required",
            "code" => "required",
            "id"    => "required"
        ]);

        Models::updateColor($request->id, $request->name, $request->code);

        return \redirect("colors/show");
    }

    function insertColor(Request $request)
    {
        $validate = $request->validate([
            "name" => "required",
            "code" => "required"
        ]);

        Models::insertColor($request->name, $request->code);

        return \redirect("colors/show");
    }
}
