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

use Illuminate\Support\Facades\Route;

Route::get("sales/return/add", "SalesController@addReturnPage")->middleware('cors');
Route::post("sales/return/insert", "SalesController@insertReturn")->middleware('cors');


Route::get("sales/show",            'SalesController@show')->middleware('cors');
Route::get("sales/sold",            'SalesController@allItemsSold')->middleware('cors');
Route::get("sales/show/{id}",       'SalesController@show')->middleware('cors');
Route::get("sales/invoice/{id}",       'SalesController@invoice')->middleware('cors');
Route::get("sales/items/{salesID}",       'SalesController@sales')->middleware('cors');
Route::get("sales/add",            'SalesController@addPage')->middleware('cors');
Route::post("sales/insert",        'SalesController@insert')->middleware('cors');
Route::post("sales/add/payment",        'SalesController@insertPayment')->middleware('cors');



//Finished Prods
Route::get("finished/show",                 'FinishedController@show')->middleware('cors');
Route::get("finished/add",                  'FinishedController@addPage')->middleware('cors');
Route::get("finished/models",                  'FinishedController@models')->middleware('cors');
Route::post("finished/insert",              'FinishedController@insert')->middleware('cors');
Route::get("finished/empty/{id}",              'FinishedController@emptyFinished')->middleware('cors');
Route::get("finished/empty/all",              'FinishedController@resetAll')->middleware('cors');
Route::post("finished/link",              'FinishedController@insertModel')->middleware('cors');
Route::post("finished/edit/price",              'FinishedController@editPrice')->middleware('cors');
Route::post("finished/upload/models",              'FinishedController@uploadModels')->middleware('cors');
Route::get('finished/show/{id}',        'FinishedController@showFinished')->middleware('cors');
Route::get('finished/hide/{id}',        'FinishedController@hideFinished')->middleware('cors');

//Clients Routes
Route::get("clients/trans/prepare",     'ClientsController@report')->middleware('cors');
Route::get("clients/trans/quick",     'ClientsController@quickReport')->middleware('cors');
Route::get("clients/trans/quick/{id}",     'ClientsController@quickReport')->middleware('cors');
Route::get("clients/trans/add",       'ClientsController@addTransPage')->middleware('cors');
Route::post("clients/trans/insert",   'ClientsController@insertTrans')->middleware('cors');
Route::post("clients/trans/error",   'ClientsController@markError')->middleware('cors');
Route::post("clients/trans/unmark",   'ClientsController@unmarkError')->middleware('cors');
Route::post("clients/account/statement", 'ClientsController@accountStatement')->middleware('cors');
Route::post("clients/main/account", 'ClientsController@mainReport')->middleware('cors');

Route::post("clients/trans/hide",   'ClientsController@hideTrans')->middleware('cors');
Route::get("clients/trans/show/{id}",   'ClientsController@showTrans')->middleware('cors');

Route::get('clients/target/current', 'ClientsController@currentTargets')->middleware('cors');
Route::get('clients/generate/targets', 'ClientsController@generateTargets')->middleware('cors');
Route::get('clients/target/history', 'ClientsController@prepareHistoryTarget')->middleware('cors');
Route::post('clients/target/load', 'ClientsController@historyPage')->middleware('cors');
Route::post('clients/set/targets', 'ClientsController@setTarget')->middleware('cors');

Route::get("clients/show",        'ClientsController@home')->middleware('cors');
Route::get("clients/add",         'ClientsController@addPage')->middleware('cors');
Route::get("clients/edit/{id}",   'ClientsController@edit')->middleware('cors');
Route::post("clients/insert",     'ClientsController@insert')->middleware('cors');
Route::post("clients/update",     'ClientsController@updateClient')->middleware('cors');


//Production Inventory
Route::get('raw/prod/show', 'RawInventoryController@showProduction')->middleware('cors');
Route::get('raw/prod/add', 'RawInventoryController@addProd')->middleware('cors');
Route::get('raw/prod/full/insert/{id}', 'RawInventoryController@insertProdFull')->middleware('cors');
Route::post('raw/prod/insert', 'RawInventoryController@insertProd')->middleware('cors');
Route::post('raw/from/prod', 'RawInventoryController@insertFromProd')->middleware('cors');



//Raw Inventory
Route::get('rawinventory/show'  ,   'RawInventoryController@showAvailable')->middleware('cors');
Route::get('rawinventory/model/{rawID}/{typeID}/{suppID}',   'RawInventoryController@showModelRolls')->middleware('cors');
Route::get('rawinventory/bytrans/{tran}'  ,   'RawInventoryController@showFullTransaction')->middleware('cors');
Route::get('rawinventory/add'   ,   'RawInventoryController@addPage')->middleware('cors');
Route::post('rawinventory/addentry'   ,   'RawInventoryController@setEntry')->middleware('cors');
Route::get('rawinventory/cancel'   ,   'RawInventoryController@cancelEntry')->middleware('cors');
Route::get('rawinventory/tran'   ,   'RawInventoryController@transactions')->middleware('cors');
Route::get('raw/tran/add'       ,   'RawInventoryController@addTran')->middleware('cors');
Route::post('raw/tran/insert'   ,   'RawInventoryController@insertTran')->middleware('cors');
Route::post('rawinventory/insert',   'RawInventoryController@insert')->middleware('cors');
Route::post('raw/adjust/entry', 'RawInventoryController@adjustEntry')->middleware('cors');
Route::post('rawinventory/tran/addentry'   ,   'RawInventoryController@addTranEntry')->middleware('cors');

//Models
Route::get("models/show",   'ModelsController@show')->middleware('cors');
Route::get("models/edit/{id}",  'ModelsController@editModel')->middleware('cors');
Route::post("models/update",  'ModelsController@updateModel')->middleware('cors');
Route::post("models/insert",  'ModelsController@insertModel')->middleware('cors');
Route::get('models/show/{id}',        'ModelsController@showModel')->middleware('cors');
Route::get('models/hide/{id}',        'ModelsController@hideModel')->middleware('cors');

//Colors
Route::get("colors/show",   'ModelsController@showColors')->middleware('cors');
Route::get("colors/edit/{id}",  'ModelsController@editColor')->middleware('cors');
Route::post("colors/update",  'ModelsController@updateColor')->middleware('cors');
Route::post("colors/insert",  'ModelsController@insertColor')->middleware('cors');

//Brands
Route::get("brands/show",           'BrandsController@show')->middleware('cors');
Route::get("brands/edit/{id}",      'BrandsController@editBrand')->middleware('cors');
Route::post('brands/update',        'BrandsController@updateBrand')->middleware('cors');
Route::post('brands/insert',        'BrandsController@insertBrand')->middleware('cors');
Route::get('brands/show/{id}',        'BrandsController@showBrand')->middleware('cors');
Route::get('brands/hide/{id}',        'BrandsController@hideBrand')->middleware('cors');

//Raws
Route::get("raw/show",          'ModelsController@showRaw')->middleware('cors');
Route::get("raw/edit/{id}",     'ModelsController@editRaw')->middleware('cors');
Route::post('raw/update',        'ModelsController@updateRaw')->middleware('cors');
Route::post('raw/insert',       'ModelsController@insertRaw')->middleware('cors');

//Types
Route::get("types/show",          'ModelsController@showTypes')->middleware('cors');
Route::get("types/edit/{id}",     'ModelsController@editType')->middleware('cors');
Route::post('types/update',        'ModelsController@updateType')->middleware('cors');
Route::post('types/insert',       'ModelsController@insertType')->middleware('cors');

//Bank Account
Route::get("bank/show",         'BankController@show')->middleware('cors');
Route::get("bank/show/{typeID}",         'BankController@show')->middleware('cors');
Route::get("bank/add",         'BankController@addPage')->middleware('cors');
Route::post("bank/insert",         'BankController@insert')->middleware('cors');
Route::get("bank/prepare/report",         'BankController@reportPage')->middleware('cors');
Route::post("bank/report",         'BankController@report')->middleware('cors');
Route::post("bank/error",         'BankController@markError')->middleware('cors');
Route::post("bank/unmark",         'BankController@unmarkError')->middleware('cors');

//Ledger Account
Route::get("ledger/show",         'LedgerController@show')->middleware('cors');
Route::get("ledger/show/{subtypeID}",         'LedgerController@show')->middleware('cors');
Route::get("ledger/add",         'LedgerController@addPage')->middleware('cors');
Route::post("ledger/insert",         'LedgerController@insert')->middleware('cors');
Route::get("ledger/prepare/report",         'LedgerController@reportPage')->middleware('cors');
Route::post("ledger/report",         'LedgerController@report')->middleware('cors');
Route::post("ledger/error",         'LedgerController@markError')->middleware('cors');
Route::post("ledger/unmark",         'LedgerController@unmarkError')->middleware('cors');

//Cash Account
Route::get("cash/show",             'CashController@show')->middleware('cors');
Route::get("cash/expenses/show",             'CashController@showTotalTypesPaid')->middleware('cors');
Route::get("cash/show/{typeID}",             'CashController@show')->middleware('cors');
Route::get("cash/add",              'CashController@addPage')->middleware('cors');
Route::post("cash/insert",          'CashController@insert')->middleware('cors');
Route::get("cash/prepare/report",   'CashController@reportPage')->middleware('cors');
Route::post("cash/report",          'CashController@report')->middleware('cors');
Route::post("cash/error",         'CashController@markError')->middleware('cors');
Route::post("cash/unmark",         'CashController@unmarkError')->middleware('cors');

//TransactionTypes
Route::get("transtype/show",           'TransTypeController@showTransType')->middleware('cors');
Route::get("transtype/edit/{id}",      'TransTypeController@editTransType')->middleware('cors');
Route::post('transtype/update',        'TransTypeController@updateTransType')->middleware('cors');
Route::post('transtype/insert',        'TransTypeController@insertTransType')->middleware('cors');
Route::get("transsubtype/edit/{id}",      'TransTypeController@editTransSubType')->middleware('cors');
Route::get("transsubtype/delete/trans/{id}",      'TransTypeController@deleteTransactionsByType')->middleware('cors');
Route::post('transsubtype/update',        'TransTypeController@updateTransSubType')->middleware('cors');
Route::post('transsubtype/insert',        'TransTypeController@insertTransSubType')->middleware('cors');

//LedgervTypes
Route::get(" ledger/types/show",           'LedgerTypeController@showLedgerType')->middleware('cors');
Route::get(" ledger/types/edit/{id}",      'LedgerTypeController@editLedgerType')->middleware('cors');
Route::post('ledger/types/update',        'LedgerTypeController@updateLedgerType')->middleware('cors');
Route::post('ledger/types/insert',        'LedgerTypeController@insertLedgerType')->middleware('cors');
Route::get(" ledger/subtypes/edit/{id}",      'LedgerTypeController@editLedgerSubType')->middleware('cors');
Route::post('ledger/subtypes/update',        'LedgerTypeController@updateLedgerSubType')->middleware('cors');
Route::post('ledger/subtypes/insert',        'LedgerTypeController@insertLedgerSubType')->middleware('cors');


//Suppliers Routes
Route::get("suppliers/trans/prepare",     'SuppliersController@report')->middleware('cors');
Route::get("suppliers/trans/quick",     'SuppliersController@quickReport')->middleware('cors');
Route::get("suppliers/trans/quick/{id}",     'SuppliersController@quickReport')->middleware('cors');
Route::get("suppliers/trans/add",       'SuppliersController@addTransPage')->middleware('cors');
Route::post("suppliers/trans/insert",   'SuppliersController@insertTrans')->middleware('cors');
Route::post("suppliers/trans/error",   'SuppliersController@markError')->middleware('cors');
Route::post("suppliers/trans/unmark",   'SuppliersController@unmarkError')->middleware('cors');
Route::post("suppliers/account/statement", 'SuppliersController@accountStatement')->middleware('cors');
Route::post("suppliers/main/account", 'SuppliersController@mainReport')->middleware('cors');

Route::post("suppliers/trans/hide",   'SuppliersController@hideTrans')->middleware('cors');
Route::get("suppliers/trans/show/{id}",   'SuppliersController@showTrans')->middleware('cors');

Route::get("suppliers/show",        'SuppliersController@home')->middleware('cors');
Route::get("suppliers/add",         'SuppliersController@addPage')->middleware('cors');
Route::get("suppliers/edit/{id}",   'SuppliersController@edit')->middleware('cors');
Route::post("suppliers/insert",     'SuppliersController@insert')->middleware('cors');
Route::post("suppliers/update",     'SuppliersController@updateSupplier')->middleware('cors');

Route::get("suppliers/types/show",       'SuppliersController@types')->middleware('cors');
Route::get("suppliers/types/add",        'SuppliersController@addType')->middleware('cors');
Route::get("suppliers/types/edit/{id}",     'SuppliersController@editType')->middleware('cors');
Route::get("suppliers/types/delete/{id}",     'SuppliersController@deleteType')->middleware('cors');
Route::post("suppliers/types/insert",       'SuppliersController@insertType')->middleware('cors');
Route::post("suppliers/types/update",       'SuppliersController@update')->middleware('cors');

//Users Routes
Route::get('users/show',            'UsersController@home')->middleware('cors');
Route::get("users/add",             'UsersController@addPage')->middleware('cors');
Route::get("users/edit/{id}",       'UsersController@edit')->middleware('cors');
Route::post("users/insert",         'UsersController@insert')->middleware('cors');
Route::post("users/update",         'UsersController@update')->middleware('cors');


//Auth::routes(['register' => false])->middleware('cors');

Route::get('logout', 'HomeController@logout')->name('logout')->middleware('cors');
Route::post('login', 'HomeController@login')->name('login')->middleware('cors');
Route::get('login', 'HomeController@login')->name('loginHome')->middleware('cors');
Route::get('/summary', 'HomeController@index')->name('summary')->middleware('cors');
Route::get('/home', 'HomeController@empty')->name('home')->middleware('cors');
Route::get('/', 'HomeController@empty')->name('summary')->middleware('cors');
