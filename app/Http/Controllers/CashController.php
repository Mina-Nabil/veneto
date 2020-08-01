<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cash;
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

    function showTotalTypesPaid()
    {

        $thisYear = new DateTime('now');
        $startOfYear = new DateTime($thisYear->format('Y') . '-01-01');
        $endOfYear = new DateTime($thisYear->format('Y') . '-12-31');

        $data['masareef'] = [];
        for ($i = 1; $i <= 13; $i++) {
            $data['totals']['masareef'][$i] = 0;
        }
        $types = TransType::getTransTypes();
        foreach ($types as $type) {
            $data['masareef'][$type->id]['typeID'] = $type->id;
            $data['masareef'][$type->id]['typeName'] = $type->TRTP_NAME;

            for ($i = 1; $i <= 12; $i++) {
                $tmpMonth = new DateTime($thisYear->format('Y') . '-' . $i . '-01');
                $data['masareef'][$type->id][$i] = Cash::getCashSpent($tmpMonth->format('Y-m-d'), $tmpMonth->format('Y-m-t'), $type->id);
                $data['totals']['masareef'][$i] += ($data['masareef'][$type->id][$i]->totalIn - $data['masareef'][$type->id][$i]->totalOut);
            }
            $data['masareef'][$type->id][13] = Cash::getCashSpent($startOfYear->format('Y-m-d'), $endOfYear->format('Y-m-d'), $type->id);
            $data['totals']['masareef'][13] += ($data['masareef'][$type->id][13]->totalIn - $data['masareef'][$type->id][13]->totalOut);
        }

        return view('cash.expenses', $data);
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
