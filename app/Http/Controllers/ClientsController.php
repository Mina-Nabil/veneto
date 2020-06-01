<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Clients;
use Illuminate\Support\Facades\Validator;

class ClientsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    ////////Client Transactions/////////////
    function quickReport($id=null){

        $data['ops'] = Clients::getTrans($id);
        $data['isClient'] = ($id != null);
        if($data['isClient']){
            $data['totals'] = Clients::getLastTransaction($id);
            $data['client'] = Clients::getClient($id);
        }
        return view("clients.report", $data);
    }

    //prepare report page
    function report(){
        
        $data['clients'] = Clients::getClients();

        $data['accountStatFormURL'] = url('clients/account/statement');
        $data['mainReportFormURL'] = url('clients/main/account');

        return view('clients.prepare', $data);
    }
    
    function accountStatement(Request $request){

        $data['arr'] = Clients::getAccountStatement($request->client, $request->from, $request->to);
        $data['client'] = Clients::getClient($request->client);

        if($data['client'] == null) return abort(404);

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

        $data['reportTitle'] = $data['client']->CLNT_NAME . " Account Statement";
        $data['reportDesc'] = $data['client']->CLNT_NAME . " Current Balance is " . $data['client']->CLNT_BLNC;

        $data['startBalance'] = $data['balance'] - $data['totals']->totalPurch + $data['totals']->totalDisc + $data['totals']->totalCash +
                                $data['totals']->totalReturn + $data['totals']->totalNotes ;

        return view('clients.accnt_stat', $data);

    }

    function mainReport(Request $request){
        // $data['ops'] = Clients::getTotals($request->from, $request->to, 0);
        $data['onlineOps'] = Clients::getTotals($request->from, $request->to, 1);
        $data['koloTotals'] = Clients::getFullTotals($request->from, $request->to);
        return view('clients.main_report', $data);
    }

    function addTransPage(){

        $data['clients']  = Clients::getClients();

        $data['pageTitle']  = "Add Client Operation";
        $data['pageDescription']    = "Add New Client Operation";
        $data['formURL']            = url("clients/trans/insert");

        return view('clients.add_trans', $data);
    }
    
    function insertTrans(Request $request){

        Clients::insertTrans( $request->client, $request->sales, $request->cash,  $request->notes,
                              $request->disc, $request->return, $request->comment, $request->desc);
        
        return redirect("clients/trans/quick");

    }


    function markError(Request $request){

        $validator = Validator::make($request->all(), [
            'tranId' => 'required'
        ]);

        if ($validator->fails())
            echo 0;
        else
            echo Clients::correctFaultyTran($request->tranId);

        return;
    }

    function unmarkError(Request $request){

        $validator = Validator::make($request->all(), [
            'tranId' => 'required'
        ]);

        if ($validator->fails())
            echo 0;
        else
            echo Clients::unmarkTranError($request->tranId);

        return;
    }
    
    ///////////Client Pages///////////////////
    function home(){
        $data['clients']  = Clients::getClients();
        $data['total']      = Clients::getTotalBalance(); 
        return view('clients.home', $data);
    }

    function addPage(){

        $data['pageTitle'] = "New Client";
        $data['pageDescription'] = "Add New Client - Data Required: Name - Balance";
        $data['formURL']        =   url("clients/insert");

        return view("clients.add", $data);
    }

    function insert(Request $request){
        $validatedData = $request->validate([
            "name"  => "required",
            "balance" => "required"
        ]);
        $isOnline = ($request->isOnline == "on") ? 1 : 0;
        Clients::insert($request->name, $request->arbcName, $request->balance, $request->address, $request->tele, $request->comment, $isOnline);

        return redirect("clients/show");
    }

    function edit($id){

        $data['client']   =   Clients::getClient($id);
        if($data['client'] == null )abort(404);
        $data['pageTitle'] = "Client: " . $data['client']->CLNT_NAME;
        $data['pageDescription'] = "Edit ("  . $data['client']->CLNT_NAME .  ") - Data Required: Name - Balance";
        $data['formURL']        =   url("clients/update");

        return view("clients.add", $data);  

    }

    function updateClient(Request $request){
        $validatedData = $request->validate([
            "id"    => "required",
            "name"  => "required",
            "balance" => "required"
        ]);
        $isOnline = ($request->isOnline == "on") ? 1 : 0;
        Clients::updateClient($request->id, $request->name, $request->arbcName, $request->balance, $request->address, $request->tele, $request->comment, $isOnline);

        return redirect("clients/show");
    }

}
