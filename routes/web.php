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
use Illuminate\Support\Carbon;


Route::get('/', function () {
    return view('welcome');
});

Route::get('test', function(){
	// return var_dump(Carbon::create(2012, 9, 5, 23, 26, 11));
	echo Carbon::now()->timestamp.'<br>';
	echo time();

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

	Route::get('list', 'ProjectsController@GetList');
	Route::get('add', 'ProjectsController@GetAdd');
	Route::post('add', 'ProjectsController@PostAdd');

	Route::group(['middleware'=>'AdminLogin'], function(){
		Route::get('delete/{id}', 'ProjectsController@GetDelete');
		Route::get('edit/{id}', 'ProjectsController@GetEdit');
		Route::post('edit/{id}', 'ProjectsController@PostEdit');
	});
});

