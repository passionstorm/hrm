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
	return view('pages.error_404');
});

Route::get('login', 'UserController@GetLogin');
Route::post('login', 'UserController@PostLogin');
Route::get('logout', 'UserController@GetLogout');
Route::get('index', 'UserController@GetIndexPage');

Route::group(['middleware'=>'AdminLogin'],function(){

	Route::get('register', 'UserController@GetRegister');
	Route::post('register', 'UserController@PostRegister');

});

Route::group(['prefix'=>'users'], function(){

	Route::get('list', 'UserController@GetList')->middleware('PreventMem');

	Route::get('edit/{id}', 'UserController@GetEdit')->middleware('login');
	Route::post('edit/{id}', 'UserController@PostEdit');

	Route::group(['middleware'=>'AdminLogin'], function(){
		Route::get('delete/{id}', 'UserController@GetDeleteUser');
	});

});

Route::group(['prefix'=>'projects', 'middleware'=>'PreventMem'], function(){

	// Route::get('list', 'ProjectsController@GetList');
	Route::get('add', 'ProjectsController@GetAdd');

});

