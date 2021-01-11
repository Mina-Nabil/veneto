<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //$this->call(UsersTableSeeder::class);

        // DB::table('users')->insert([
        //     'username' => "dev",
        //     'fullname' => 'Mina Nabil',
        //     'password' => bcrypt('dev@veneto'),
        //     'mobNumber' => "01225212014",
        // ]);

        // DB::table('cash')->insert([
        //     'CASH_NAME' =>  'Initial Balance',
        //     'CASH_IN'   =>  0,
        //     'CASH_OUT'  =>  0,
        //     'CASH_BLNC'  => 55000,
        //     'CASH_CMNT' =>  null,
        //     'CASH_DATE' =>  date('Y-m-d')
        // ]);

        // DB::table('bank')->insert([
        //     'BANK_NAME' =>  'Initial Balance',
        //     'BANK_IN'   =>  0,
        //     'BANK_OUT'  =>  0,
        //     'BANK_BLNC'  => -10000,
        //     'BANK_CMNT' =>  null,
        //     'BANK_DATE' =>  date('Y-m-d')
        // ]);

        DB::table('ledger')->insert([
            'LDGR_NAME' =>  'Initial Balance',
            'LDGR_IN'   =>  0,
            'LDGR_OUT'  =>  0,
            'LDGR_BLNC'  => 0,
            'LDGR_CMNT' =>  null,
            'LDGR_DATE' =>  date('Y-m-d')
        ]);
    }
}
