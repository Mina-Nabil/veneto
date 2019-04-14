<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        DB::table('user_types')->insert([
            'name' => "admin"
        ]);

        DB::table('users')->insert([
            'username' => "admin",
            'fullname' => Str::random(4).' Nabil',
            'password' => bcrypt('admin'),
            'mobNumber' => "01225212014",
            'typeID' => 1,
        ]);
    }
}
