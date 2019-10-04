<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UsersController extends Controller
{
    //
    function home(){
        $table = Users::getUsers();
        return view("users.home", ["users" => $table]);
    }

    function addpage(){
        return view("users.add");
    }

    function insert(Request $request){

        $validatedDate = $request->validate([
            'name'      =>  'required',
            'pass'      =>  'required',
        ]);

        $username   =   $request->name;
        $pass       =   $request->pass;
        $fullName   =   $request->fullName;
        $mobNumber  =   $request->mob;

        if ($request->hasFile('photo')) {
            $path = $request->photo->store('images/users', 'public');
        }

        $id = User::insertUser($username, $pass, $fullName, $mobNumber, $path);

    }

    function edit(){

    }
}
