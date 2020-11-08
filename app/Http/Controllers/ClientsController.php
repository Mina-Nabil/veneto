<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Clients;
use App\Target;
use DateTime;
use Illuminate\Support\Facades\Validator;

class ClientsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    ////////Client Transactions/////////////
    function quickReport($id = null)
    {

        $data['ops'] = Clients::getTrans($id);
        $data['isClient'] = ($id != null);
        if ($data['isClient']) {
            $data['totals'] = Clients::getLastTransaction($id);
            $data['client'] = Clients::getClient($id);
        }
        return view("clients.report", $data);
    }

    ///////prepare report page
    function report()
    {

        $data['clients'] = Clients::getClients();

        $data['accountStatFormURL'] = url('clients/account/statement');
        $data['mainReportFormURL'] = url('clients/main/account');

        return view('clients.prepare', $data);
    }

    function accountStatement(Request $request)
    {

        $data['arr'] = Clients::getAccountStatement($request->client, $request->from, $request->to);
        $data['client'] = Clients::getClient($request->client);

        if ($data['client'] == null) return abort(404);

        $data['totals'] = $data['arr']['totals'];
        $data['ops']    = $data['arr']['trans'];
        $data['balance']    = $data['arr']['balance'];

        if ($data['totals'] == null) {
            $data['totals'] = (object)[
                'totalPurch' => 0,
                'totalDisc' => 0,
                'totalReturn' => 0,
                'totalNotes' => 0,
                'totalCash' => 0
            ];
        }

        $data['reportTitle'] = $data['client']->CLNT_NAME . " Account Statement";
        $data['reportDesc'] = $data['client']->CLNT_NAME . " Current Balance is " . $data['client']->CLNT_BLNC;

        $data['startBalance'] = $data['balance'] - $data['totals']->totalPurch + $data['totals']->totalDisc + $data['totals']->totalCash +
            $data['totals']->totalReturn + $data['totals']->totalNotes;

        return view('clients.accnt_stat', $data);
    }

    function mainReport(Request $request)
    {
        $data['ops'] = Clients::getTotals($request->from, $request->to, 0);
        $data['ops']['data']->sort('CLNT_SRNO');
        $data['onlineOps'] = Clients::getTotals($request->from, $request->to, 1);
        $data['onlineOps']['data']->sort('CLNT_SRNO');
        $data['viaVenetoOps'] = Clients::getTotals($request->from, $request->to, 2);
        $data['viaVenetoOps']['data']->sort('CLNT_SRNO');
        $data['prodOps'] = Clients::getTotals($request->from, $request->to, 3);
        $data['prodOps']['data']->sort('CLNT_SRNO');
        $data['procOps'] = Clients::getTotals($request->from, $request->to, 4);
        $data['procOps']['data']->sort('CLNT_SRNO');
        $data['koloTotals'] = Clients::getFullTotals($request->from, $request->to);
        return view('clients.main_report', $data);
    }

    function addTransPage()
    {

        $data['clients']  = Clients::getClients();

        $data['pageTitle']  = "Add Client Operation";
        $data['pageDescription']    = "Add New Client Operation";
        $data['formURL']            = url("clients/trans/insert");

        return view('clients.add_trans', $data);
    }

    function insertTrans(Request $request)
    {

        Clients::insertTrans(
            $request->client,
            $request->sales,
            $request->cash,
            $request->notes,
            $request->disc,
            $request->return,
            $request->comment,
            $request->desc
        );

        return redirect("clients/trans/quick");
    }


    function markError(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'tranId' => 'required'
        ]);

        if ($validator->fails())
            echo 0;
        else
            echo Clients::correctFaultyTran($request->tranId);

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
            echo Clients::unmarkTranError($request->tranId);

        return;
    }

    //////////////////////////Targets Functions

    function historyPage(Request $request)
    {
        $request->validate([
            "month" => "required|numeric",
            "year" => "required|numeric",
        ]);
        $data['targets'] =  Target::getTargets($request->year, $request->month);
        $data['totals'] = Target::getTargetTotals($request->year, $request->month);

        $data['year'] = $request->year;
        $data['month'] = $request->month;
        $data['isHistory'] = true;
        
        return view('clients.targets', $data);
    }

    function currentTargets()
    {
        $now = new DateTime();
        $targets =  Target::getTargets($now->format('Y'), $now->format('m'));
        if (count($targets) == 0) {
            Target::createYearlyTargets($now->format('Y'));
            $targets = Target::getTargets($now->format('Y'), $now->format('m'));
        }
        $data['targets'] = $targets;
        $data['totals'] = Target::getTargetTotals($now->format('Y'), $now->format('m'));

        $data['year'] = $now->format('Y');
        $data['month'] = $now->format('M');
        $data['isHistory'] = false;

        return view('clients.targets', $data);
    }

    function prepareHistoryTarget()
    {
        $data['targetHistoryFormURL'] = url('clients/target/load');
        return view('clients.prepare_target', $data);
    }

    function generateTargets()
    {
        $now = new DateTime();
        Target::generateTargets($now->format('Y'));
        return redirect('clients/target/current');
    }

    function setTarget(Request $request){
        $request->validate([
            "id" => "required",
            "money" => 'required|numeric|min:0',
            "bank" => 'required|numeric|min:0',
        ]);

        $target = Target::findOrFail($request->id);
        $target->TRGT_MONY = $request->money;
        $target->TRGT_BANK = $request->bank;
        return (($target->save()) ? '1' : 'failed');
    }



    ////////////////////////////////////////////////////////

    ///////////Client Pages///////////////////
    function home()
    {
        $data['veneto']  = Clients::getClients(0);
        $data['online']  = Clients::getClients(1);
        $data['via']  = Clients::getClients(2);
        $data['prod']  = Clients::getClients(3);
        $data['proc']  = Clients::getClients(4);
        $data['totalVeneto']      = Clients::getTotalBalance(0);
        $data['totalOnline']      = Clients::getTotalBalance(1);
        $data['totalVia']      = Clients::getTotalBalance(2);
        $data['totalProd']      = Clients::getTotalBalance(3);
        $data['totalProc']      = Clients::getTotalBalance(4);
        $data['total']      = Clients::getTotalBalance();
        return view('clients.home', $data);
    }

    function addPage()
    {

        $data['pageTitle'] = "New Client";
        $data['pageDescription'] = "Add New Client - Data Required: Name - Balance";
        $data['formURL']        =   url("clients/insert");

        return view("clients.add", $data);
    }

    function insert(Request $request)
    {
        $validatedData = $request->validate([
            "name"  => "required",
            "balance" => "required"
        ]);
        Clients::insert($request->name, $request->arbcName, $request->balance, $request->address, $request->tele, $request->comment, $request->isOnline, $request->serial);

        return redirect("clients/show");
    }

    function edit($id)
    {

        $data['client']   =   Clients::getClient($id);
        if ($data['client'] == null) abort(404);
        $data['pageTitle'] = "Client: " . $data['client']->CLNT_NAME;
        $data['pageDescription'] = "Edit ("  . $data['client']->CLNT_NAME .  ") - Data Required: Name - Balance";
        $data['formURL']        =   url("clients/update");

        return view("clients.add", $data);
    }

    function updateClient(Request $request)
    {
        $validatedData = $request->validate([
            "id"    => "required",
            "name"  => "required",
            "balance" => "required"
        ]);

        Clients::updateClient($request->id, $request->name, $request->arbcName, $request->balance, $request->address, $request->tele, $request->comment, $request->isOnline, $request->serial);

        return redirect("clients/show");
    }
}
