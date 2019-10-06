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


//Suppliers Routes
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
