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

    public function show()
    {
        $data['finished'] = Finished::getAvailable();
        return view("finished.home", $data);
    }

    public function models()
    {
        $data['models'] = Models::getModelNames();
        $data['brands'] = Brands::getBrands();

        $data['finished'] = Finished::getAllFinished(1);

        $data['pageTitle'] = "Add New Finished Model";
        $data['pageDescription']  = "Link a brand with a model";
        $data['formURL'] = url("finished/link");

        return view('finished.models', $data);
    }

    public function insertModel(Request $request)
    {
        $request->validate([
            'model' => 'required',
            'brand' => 'required'
        ]);
        Finished::insertFinished($request->model, $request->brand);
        return redirect('finished/models');
    }

    public function emptyFinished($finishedID)
    {
        Finished::emptyInventory($finishedID);
        return redirect('finished/show');
    }

    public function resetAll()
    {
        $res = Finished::resetInventory();
        return redirect('finished/show');
    }

    public function hideModels()
    {
        Finished::hideAll();
        return redirect('finished/models');
    }

    public function addPage()
    {

        $data['finished'] = Finished::getAllFinished();

        $data['pageTitle'] = "New Finished Entry";
        $data['pageDescription']  = "Add new finished entry";
        $data['formURL'] = url("finished/insert");

        return view('finished.add', $data);
    }

    public function insert(Request $request)
    {
        Finished::insertFinishedEntry($this->getItemsArr($request));
        return redirect("finished/show");
    }

    public function editPrice(Request $request)
    {
        Finished::updatePrice($request->id, $request->price);
        return back();
    }

    private function getItemsArr($request)
    {
        $ret = array();
        foreach ($request->finished as $key => $item) {
            array_push($ret, [
                "finished"     => $item,
                "amount36"     => ($request->amount36[$key]) ? $request->amount36[$key] : 0,
                "amount38"     => ($request->amount38[$key]) ? $request->amount38[$key] : 0,
                "amount40"     => ($request->amount40[$key]) ? $request->amount40[$key] : 0,
                "amount42"     => ($request->amount42[$key]) ? $request->amount42[$key] : 0,
                "amount44"     => ($request->amount44[$key]) ? $request->amount44[$key] : 0,
                "amount46"     => ($request->amount46[$key]) ? $request->amount46[$key] : 0,
                "amount48"     => ($request->amount48[$key]) ? $request->amount48[$key] : 0,
                "amount50"     => ($request->amount50[$key]) ? $request->amount50[$key] : 0,
                "price"     => $request->price[$key],
            ]);
        }
        return $ret;
    }

    function hideFinished($id)
    {

        Finished::toggleHidden($id, 1);

        return back();
    }
    function showFinished($id)
    {

        Finished::toggleHidden($id, 0);

        return back();
    }

    //////////Excel uploads
    public function uploadModels(Request $request)
    {

        $request->validate([
            'modelsFile' => "required|mimes:xlsx"
        ]);

        $fileName = $request->file("modelsFile")->getPathname();

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($fileName);

        $worksheet = $spreadsheet->getActiveSheet();
        // Get the highest row and column numbers referenced in the worksheet
        $highestRow = $worksheet->getHighestRow(); // e.g. 10
        $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 5


        for ($row = 1; $row <= $highestRow; $row++) {

            $brandValue = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
            $modelValue = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
            if (isset($brandValue) && is_string($brandValue) && strlen($brandValue) > 0 && isset($modelValue) && strlen($modelValue) > 0) {
                $brandID = Brands::getBrandIDByName($brandValue);
                $modelID = Models::getModelIDByName($modelValue);
                if (isset($brandID) && is_int($brandID) && isset($modelID) && is_int($modelID)) {
                    Finished::insertFinished($modelID, $brandID);
                }
            }
            return redirect('finished/add');
        }
    }
}
