<?php

namespace App\Http\Controllers;

use App\GAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GAccountsController extends Controller
{
    private $homeURL = "accounts/home";

    function __construct()
    {
        $this->middleware('auth');
    }

    public function home()
    {
        //Accounts and Transactions
        $data['accounts'] = GAccount::getAllGeneralAccounts();
        foreach ($data['accounts'] as $account) {
            $data['trans'][$account->id] = GAccount::getAccountTransactions($account->id, 20);
        }
        $data['titles'] = GAccount::getAccountTitles();

        //URLs
        $data['insertAccountURL'] = url('accounts/insert/new');
        $data['insertTransURL'] = url('accounts/insert/trans');
        $data['updateAccount'] = url('accounts/update');
        $data['deleteAccount'] = url('accounts/delete');

        return view('accounts.home', $data);
    }

    public function queryPage()
    {
        $data['accounts'] = GAccount::getAllGeneralAccounts();
        return view("accounts.query", $data);
    }

    public function queryLoad(Request $request)
    {
        $request->validate([
            "accountID" => "required|exists:gen_accounts,id",
            "from"  => "required",
            "to"  => "required",
        ]);

        $data['from'] = $request->from;
        $data['to'] = $request->to;
        $account = GAccount::getAccount($request->accountID);
        $data['account'] = $account->GNAC_NAME;
        
        $data['trans'] = GAccount::getAccountTransactions($request->accountID, null, $request->from, $request->to);

        return view("accounts.loadQuery", $data);
    }

    public function insertAccount(Request $request)
    {
        $request->validate([
            "titleID"   =>  "required|exists:gen_accounts_title,id",
            "nature"    =>  "required",
            "name"      =>  "required",
        ]);

        GAccount::insertAccount($request->titleID, $request->nature, $request->name, $request->balance, $request->arabicName, $request->desc);

        return redirect($this->homeURL);
    }

    public function insertTrans(Request $request)
    {
        $request->validate([
            "accountID"   =>  "required|exists:gen_accounts,id",
            "title"    =>  "required",
            "type"    =>  "required",
            "value"    =>  "required",
        ]);
        if ($request->type == 1) { //credit
            $credit = $request->value;
            $debit = 0;
        } elseif ($request->type == 2) { //debit
            $credit = 0;
            $debit = $request->value;
        }

        $isCash = (isset($request->isCash) && $request->isCash == true) ? true : false;
        GAccount::addTransaction($request->accountID, $request->title, $credit, $debit, $request->comment, $isCash);
       return redirect($this->homeURL);
    }

    public function editAccount(Request $request)
    {
        $request->validate([
            "id"        =>  "required|exists:gen_accounts",
            "titleID"   =>  "required|exists:gen_accounts_title,id",
            "name"      =>  "required",
        ]);

        GAccount::editAccountInfo($request->id, $request->titleID, $request->name, $request->arabicName, $request->desc);
        return redirect($this->homeURL);
    }

    public function deleteAccount(Request $request)
    {
        $request->validate([
            "id"        =>  "required|exists:gen_accounts",
        ]);
        if (GAccount::deleteAccount($request->id))
            echo "1";
    }

    function markError(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'tranId' => 'required'
        ]);

        if ($validator->fails())
            echo 0;
        else
            echo GAccount::markTranError($request->tranId);

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
            echo GAccount::unmarkTranError($request->tranId);

        return;
    }
}
