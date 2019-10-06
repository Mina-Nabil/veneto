<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UsersController extends Controller
{
    //
    function home(){
        $table = User::getUsers();
        return view("users.home", ["users" => $table]);
    }

    function addpage(){
        return view("users.add");
    }

    function edit($id){
        
        $data['user'] = User::getUser($id);

        if($data['user']->id == null) {
            abort(404);
        } 

        $data['pageTitle'] = 'Edit ' . $data['user']->username;
        $data['pageDescription'] = ' ';
        $data['formURL']        = url('users/update');
        $data['isPassNeeded']   = false;

        return view("users.add", $data);

    }

    function update(Request $request){

        $validatedDate = $request->validate([
            'name' => 'required',
            'id'   => 'required',
        ]);
        
        $pass       =   $request->password;
        $id       =   $request->id;
        $fullName   =   $request->fullname;
        $username   =   $request->name;
        $mobNumber  =   $request->mobNumber;
        $path = null;
        $oldPath = $request->oldPath;

        if ($request->hasFile('photo')) {
            $path = $request->photo->store('images/users', 'public');
        }

        $id = User::updateUser($id, $username, $pass, $fullName, $mobNumber, $path, $oldPath);

        return redirect('users/show');
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
}
