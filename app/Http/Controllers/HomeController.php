<?php

namespace App\Http\Controllers;

use App\Cash;
use App\Clients;
use App\Sales;
use App\TransType;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Hash;
use Illuminate\Support\Facades\Date;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (!Auth::check()) return redirect('/login');

        $thisYear = new DateTime('now');

        $data = $this->getSalesSummary($thisYear);
        $data['years'] = Sales::getSalesYears();
        $data['loadYearSummaryURL'] = url('summary/year');
        $data['thisYear'] = $thisYear->format('Y');
        return view('home', $data);
    }

    public function yearlySummary(Request $request)
    {
        if (!Auth::check()) return redirect('/login');

        $request->validate([
            "year" => "required"
        ]);

        $thisYear = new DateTime($request->year . "-01-01");

        $data = $this->getSalesSummary($thisYear);
        $data['years'] = Sales::getSalesYears();
        $data['loadYearSummaryURL'] = url('summary/year');
        $data['thisYear'] = $thisYear->format('Y');
        return view('home', $data);
    }

    private function getSalesSummary(DateTime $year){
        $data['all']['months'] = [];
        $data['via']['months'] = [];
        $data['veneto']['months'] = [];
        $data['online']['months'] = [];
        $data['prod']['months'] = [];
        $data['proc']['months'] = [];

        for ($i = 1; $i <= 12; $i++) {
           
            $tmpMonth = new DateTime($year->format('Y') . '-' . $i . '-01');
            $AllTotals = Clients::getHomeTotals($tmpMonth->format('Y-m-d'), $tmpMonth->format('Y-m-t'));
            $VentoTotals = Clients::getHomeTotals($tmpMonth->format('Y-m-d'), $tmpMonth->format('Y-m-t'), 0);
            $OnlineTotals = Clients::getHomeTotals($tmpMonth->format('Y-m-d'), $tmpMonth->format('Y-m-t'), 1);
            $VieVenetoTotals = Clients::getHomeTotals($tmpMonth->format('Y-m-d'), $tmpMonth->format('Y-m-t'), 2);
            $ProdTotals = Clients::getHomeTotals($tmpMonth->format('Y-m-d'), $tmpMonth->format('Y-m-t'), 3);
            $ProcTotals = Clients::getHomeTotals($tmpMonth->format('Y-m-d'), $tmpMonth->format('Y-m-t'), 4);

            $AllTotals['monthName']         = $this->getArabicMonthName($i);
            $VentoTotals['monthName']       = $this->getArabicMonthName($i);
            $OnlineTotals['monthName']      = $this->getArabicMonthName($i);
            $VieVenetoTotals['monthName']   = $this->getArabicMonthName($i);
            $ProdTotals['monthName']   = $this->getArabicMonthName($i);
            $ProcTotals['monthName']   = $this->getArabicMonthName($i);

            array_push($data['all']['months'], $AllTotals);
            array_push($data['via']['months'], $VieVenetoTotals);
            array_push($data['veneto']['months'], $VentoTotals);
            array_push($data['online']['months'], $OnlineTotals);
            array_push($data['prod']['months'], $ProdTotals);
            array_push($data['proc']['months'], $ProcTotals);
        }



        $startOfYear = new DateTime($year->format('Y') . '-01-01');
        $endOfYear = new DateTime($year->format('Y') . '-12-31');
        $data['fullYearAll'] = Clients::getHomeTotals($startOfYear->format('Y-m-d'), $endOfYear->format('Y-m-d'));
        $data['fullYearProc'] = Clients::getHomeTotals($startOfYear->format('Y-m-d'), $endOfYear->format('Y-m-d'),4);
        $data['fullYearProd'] = Clients::getHomeTotals($startOfYear->format('Y-m-d'), $endOfYear->format('Y-m-d'),3);
        $data['fullYearVia'] = Clients::getHomeTotals($startOfYear->format('Y-m-d'), $endOfYear->format('Y-m-d'),2);
        $data['fullYearOnline'] = Clients::getHomeTotals($startOfYear->format('Y-m-d'), $endOfYear->format('Y-m-d'),1);
        $data['fullYearVeneto'] = Clients::getHomeTotals($startOfYear->format('Y-m-d'), $endOfYear->format('Y-m-d'),0);

        return $data;
    }

    public function empty(){
        if (!Auth::check()) return redirect('/login');
        return view('empty');
    }

    public function login(Request $request)
    {

        if (Auth::check()) return redirect('/home');

        $userName = $request->input('userName');
        $passWord = $request->input('passWord');

        $data['first'] = true;

        if (isset($userName)) {
            if (Auth::attempt(array('username' => $userName, 'password' => $passWord), true)) {
                return redirect('/home');
            } else {
                $data['first'] = false;
                $data['username'] = $userName;
                return view('auth/login', $data);
            }
        } else {
            $data['username'] = '';
            return view('auth/login', $data);
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

    private function getArabicMonthName($monthNumber)
    {
        switch ($monthNumber) {
            case 1:
                return 'يناير';
            case 2:
                return 'فبراير';
            case 3:
                return 'مارس';
            case 4:
                return 'ابريل';
            case 5:
                return 'مايو';
            case 6:
                return 'يونيو';
            case 7:
                return 'يوليو';
            case 8:
                return 'اغسطس';
            case 9:
                return 'سبتمبر';
            case 10:
                return 'اكتوبر';
            case 11:
                return 'نوفمبر';
            case 12:
                return 'ديسمبر';
        }
    }
}
