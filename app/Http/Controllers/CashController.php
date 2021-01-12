<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cash;
use App\Sales;
use App\TransType;
use DateTime;
use Illuminate\Support\Facades\Validator;

class CashController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    ////////////////Reports//////////////
    function reportPage()
    {
        return view("cash.report", ["reportFormURL" => url("cash/report")]);
    }

    function report(Request $request)
    {
        $data['ops'] = Cash::getReport($request->from, $request->to);
        $data['report'] = true;
        return view("cash.home", $data);
    }

    ///////////////Transactions///////////
    function show($subtypeID = 0)
    {
        $data['ops'] = Cash::getTrans($subtypeID);
        $data['report'] = false;
        return view("cash.home", $data);
    }

    function showTotalTypesPaid(Request $request)
    {
        if(isset($request->year) && is_numeric($request->year)){
            $thisYear = new DateTime($request->year . "-01-01");
        } else {
            $thisYear = new DateTime('now');
        }
        $data = $this->getYearlyExpenses($thisYear);
        $data['years'] = Cash::getCashYears();
        $data['thisYear'] = $thisYear->format('Y');
        return view('cash.expenses', $data);
    }

    private function getYearlyExpenses(DateTime $year){
   
        $startOfYear = new DateTime($year->format('Y') . '-01-01');
        $endOfYear = new DateTime($year->format('Y') . '-12-31');

        $data['masareef'] = [];
        for ($i = 1; $i <= 13; $i++) {
            $data['totals']['masareef'][$i] = 0;
        }
        $types = TransType::getTransTypes();
        foreach ($types as $type) {
            $subTypes = TransType::getTransSubTypesByType($type->id);
            foreach ($subTypes as $subType) {
                $data['masareef'][$subType->id]['typeID'] = $type->id;
                $data['masareef'][$subType->id]['typeName'] = $type->TRTP_NAME;
                $data['masareef'][$subType->id]['subTypeName'] = $subType->TRST_NAME;
                for ($i = 1; $i <= 12; $i++) {
                    $tmpMonth = new DateTime($year->format('Y') . '-' . $i . '-01');
                    $data['masareef'][$subType->id][$i] = Cash::getCashSpent($tmpMonth->format('Y-m-d'), $tmpMonth->format('Y-m-t'), $subType->id);
                    $data['totals']['masareef'][$i] += ($data['masareef'][$subType->id][$i]->totalIn - $data['masareef'][$subType->id][$i]->totalOut);
                }
                $data['masareef'][$subType->id][13] = Cash::getCashSpent($startOfYear->format('Y-m-d'), $endOfYear->format('Y-m-d'), $subType->id);
                $data['totals']['masareef'][13] += ($data['masareef'][$subType->id][13]->totalIn - $data['masareef'][$subType->id][13]->totalOut);
            }
        }
        return $data;
    }

    function addPage()
    {

        $data['pageTitle'] = "New Cash Operation";
        $data['pageDescription'] = "Add New Cash Transaction";
        $data['formURL'] = url("cash/insert");

        $data['transSubTypes'] = TransType::getTransSubTypes();

        return view("cash.add", $data);
    }

    function insert(Request $request)
    {

        $validatedDate = $request->validate([
            "name" => "required",
            'in'    => "required",
            'out'   => "required",
        ]);

        Cash::insertTran($request->name, $request->in, $request->out, $request->comment, 0, $request->typeID);

        return \redirect("cash/show");
    }

    function markError(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'tranId' => 'required'
        ]);

        if ($validator->fails())
            echo 0;
        else
            echo Cash::correctFaultyTran($request->tranId);

        return;
    }

    function unmarkError(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'tranId' => 'required'
        ]);

        if ($validator->fails())
            echo 0;
        else
            echo Cash::unmarkTranError($request->tranId);

        return;
    }
}
