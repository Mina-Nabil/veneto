<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    static function getUsers(){
        return DB::table('users')->get();
    }

    static function getUser($id){
        return DB::table('users')->find($id);
    }

    static function insertUser($user, $pass, $full, $mob, $path=null){
        return DB::table('users')->insertGetId([
            "username"  =>  $user,
            "password"  =>  Hash::make($pass),
            "fullname"  =>  $full,
            "mobNumber" =>  $mob,
            "image"     =>  $path
        ]);
    }

    static function updateUser($id, $user, $pass, $full, $mob, $path=null, $oldPath=null){

        $updateArr = [
            "username"  =>  $user,
            "fullname"  =>  $full,
            "mobNumber" =>  $mob
        ];

        if($path !== null && strcmp($path, '') != 0){
            $updateArr['image']     =   $path; 
            if (file_exists($oldPath)){
                unlink($oldPath);
            }
        }

        if($pass !== null && strcmp(trim($pass), '') != 0)
            $updateArr['password']     =   Hash::make($pass); 
    
        return DB::table('users')->where("id", $id)->update($updateArr);
    }

    static function checkCredentials($userName, $passWord){
        return DB::table('users')->where('USER_UNAME', $userName)->where('USER_PSWD', $passWord)->count();
    }
}
