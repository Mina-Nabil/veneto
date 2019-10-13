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

//Bank Account
Route::get("bank/show",         'BankController@show');
Route::get("bank/add",         'BankController@addPage');
Route::post("bank/insert",         'BankController@insert');
Route::get("bank/prepare/report",         'BankController@reportPage');
Route::post("bank/report",         'BankController@report');

//Cash Account
Route::get("cash/show",             'CashController@show');
Route::get("cash/add",              'CashController@addPage');
Route::post("cash/insert",          'CashController@insert');
Route::get("cash/prepare/report",   'CashController@reportPage');
Route::post("cash/report",          'CashController@report');


//Suppliers Routes
Route::get("suppliers/trans/prepare",     'SuppliersController@report');
Route::get("suppliers/trans/quick",     'SuppliersController@quickReport');
Route::get("suppliers/trans/quick/{id}",     'SuppliersController@quickReport');
Route::get("suppliers/trans/add",       'SuppliersController@addTransPage');
Route::post("suppliers/trans/insert",   'SuppliersController@insertTrans');
Route::post("suppliers/account/statement", 'SuppliersController@accountStatement');
Route::post("suppliers/main/account", 'SuppliersController@mainReport');

Route::get("suppliers/show",        'SuppliersController@home');
Route::get("suppliers/add",         'SuppliersController@addPage');
Route::get("suppliers/edit/{id}",   'SuppliersController@edit');
Route::post("suppliers/insert",     'SuppliersController@insert');
Route::post("suppliers/update",     'SuppliersController@updateSupplier');

Route::get("suppliers/types/show",       'SuppliersController@types');
Route::get("suppliers/types/add",        'SuppliersController@addType');
Route::get("suppliers/types/edit/{id}",     'SuppliersController@editType');
Route::post("suppliers/types/insert",       'SuppliersController@insertType');
Route::post("suppliers/types/update",       'SuppliersController@update');

//Users Routes
Route::get('users/show',            'UsersController@home');
Route::get("users/add",             'UsersController@addPage');
Route::get("users/edit/{id}",       'UsersController@edit');
Route::post("users/insert",         'UsersController@insert');
Route::post("users/update",         'UsersController@update');


//Auth::routes(['register' => false]);

Route::get('logout', 'HomeController@logout')->name('logout');
Route::post('login', 'HomeController@login')->name('login');
Route::get('login', 'HomeController@login')->name('loginHome');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/', 'HomeController@index')->name('home');
