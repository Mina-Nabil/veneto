<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//Users Routes
Route::get('users/show',            'UsersController@home');
Route::get("users/add",             'UsersController@addPage');
Route::get("users/show/{id}",       'UsersController@edit');
Route::post("users/insert",         'UsersController@insert');
Route::post("users/update",         'UsersController@update');




//Auth::routes(['register' => false]);

Route::post('logout', 'HomeController@logout')->name('logout');
Route::post('login', 'HomeController@login')->name('login');
Route::get('login', 'HomeController@login')->name('loginHome');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/', 'HomeController@index')->name('home');
