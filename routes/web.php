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
//Sales Routes
Route::get("sales/show",            'SalesController@show');
Route::get("sales/show/{id}",       'SalesController@show');
Route::get("sales/items/{salesID}",       'SalesController@sales');
Route::get("sales/add",            'SalesController@addPage');
Route::post("sales/insert",        'SalesController@insert');
Route::post("sales/add/payment",        'SalesController@insertPayment');


//Finished Prods
Route::get("finished/show",                 'FinishedController@show');
Route::get("finished/add",                  'FinishedController@addPage');
Route::post("finished/insert",              'FinishedController@insert');
Route::post("finished/edit/price",              'FinishedController@editPrice');

//Clients Routes
Route::get("clients/trans/prepare",     'ClientsController@report');
Route::get("clients/trans/quick",     'ClientsController@quickReport');
Route::get("clients/trans/quick/{id}",     'ClientsController@quickReport');
Route::get("clients/trans/add",       'ClientsController@addTransPage');
Route::post("clients/trans/insert",   'ClientsController@insertTrans');
Route::post("clients/account/statement", 'ClientsController@accountStatement');
Route::post("clients/main/account", 'ClientsController@mainReport');

Route::get("clients/show",        'ClientsController@home');
Route::get("clients/add",         'ClientsController@addPage');
Route::get("clients/edit/{id}",   'ClientsController@edit');
Route::post("clients/insert",     'ClientsController@insert');
Route::post("clients/update",     'ClientsController@updateClient');


//Production Inventory
Route::get('raw/prod/show', 'RawInventoryController@showProduction');
Route::get('raw/prod/add', 'RawInventoryController@addProd');
Route::get('raw/prod/full/insert/{id}', 'RawInventoryController@insertProdFull');
Route::post('raw/prod/insert', 'RawInventoryController@insertProd');
Route::post('raw/from/prod', 'RawInventoryController@insertFromProd');



//Raw Inventory
Route::get('rawinventory/show'  ,   'RawInventoryController@showAvailable');
Route::get('rawinventory/model/{rawID}/{typeID}/{suppID}',   'RawInventoryController@showModelRolls');
Route::get('rawinventory/bytrans/{tran}'  ,   'RawInventoryController@showFullTransaction');
Route::get('rawinventory/add'   ,   'RawInventoryController@addPage');
Route::post('rawinventory/addentry'   ,   'RawInventoryController@setEntry');
Route::get('rawinventory/cancel'   ,   'RawInventoryController@cancelEntry');
Route::get('rawinventory/tran'   ,   'RawInventoryController@transactions');
Route::get('raw/tran/add'       ,   'RawInventoryController@addTran');
Route::post('raw/tran/insert'   ,   'RawInventoryController@insertTran');
Route::post('rawinventory/insert',   'RawInventoryController@insert');

//Models
Route::get("models/show",   'ModelsController@showModels');
Route::get("models/edit/{id}",  'ModelsController@editModel');
Route::post("models/update",  'ModelsController@updateModel');
Route::post("models/insert",  'ModelsController@insertModel');

//Colors
Route::get("colors/show",   'ModelsController@showColors');
Route::get("colors/edit/{id}",  'ModelsController@editColor');
Route::post("colors/update",  'ModelsController@updateColor');
Route::post("colors/insert",  'ModelsController@insertColor');

//Brands
Route::get("brands/show",           'BrandsController@showBrand');
Route::get("brands/edit/{id}",      'BrandsController@editBrand');
Route::post('brands/update',        'BrandsController@updateBrand');
Route::post('brands/insert',        'BrandsController@insertBrand');

//Raws
Route::get("raw/show",          'ModelsController@showRaw');
Route::get("raw/edit/{id}",     'ModelsController@editRaw');
Route::post('raw/update',        'ModelsController@updateRaw');
Route::post('raw/insert',       'ModelsController@insertRaw');

//Types
Route::get("types/show",          'ModelsController@showTypes');
Route::get("types/edit/{id}",     'ModelsController@editType');
Route::post('types/update',        'ModelsController@updateType');
Route::post('types/insert',       'ModelsController@insertType');

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
