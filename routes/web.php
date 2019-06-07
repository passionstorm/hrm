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

Route::get('/', function () {
    return view('welcome');
});

Route::get('test', function(){
	echo var_dump(Auth::user()->created_at->toFormattedDateString());
});

Route::get('login', 'UserController@GetLogin');
Route::post('login', 'UserController@PostLogin');
Route::get('logout', 'UserController@GetLogout');


Route::group(['prefix'=>'admin', 'middleware'=>'AdminLogin'],function(){
	Route::get('index', function(){
		return view('admin.pages.index');
	});

	Route::get('register', 'UserController@GetRegister');
	Route::post('register', 'UserController@PostRegister');

	Route::group(['prefix'=>'users'], function(){
		Route::get('list', 'UserController@GetList');
	});
});

Route::get('mempage', function(){
	return view('mempage');
})->middleware('login');