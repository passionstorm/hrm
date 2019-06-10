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
	echo Constants::COUNTRIES['vn'];

});

Route::get('login', 'UserController@GetLogin');
Route::post('login', 'UserController@PostLogin');
Route::get('logout', 'UserController@GetLogout');
Route::get('index', 'UserController@GetIndexPage');


Route::group(['prefix'=>'users'], function(){

	Route::get('list', 'UserController@GetList')->middleware('PreventMem');

	Route::group(['middleware'=>'AdminLogin'], function(){
		Route::get('delete/{id}', 'UserController@GetDeleteUser');
	});

	Route::get('post/{id?}', 'UserController@GetPost');
	Route::post('post/{id?}', 'UserController@PostPost');

});

Route::group(['prefix'=>'projects', 'middleware'=>'PreventMem'], function(){

	Route::get('list', 'ProjectsController@GetList');

	Route::group(['middleware'=>'AdminLogin'], function(){
		Route::get('delete/{id}', 'ProjectsController@GetDelete');
	});

	Route::get('post/{id?}', 'ProjectsController@GetPost');
	Route::post('post/{id?}', 'ProjectsController@PostPost');

});

Route::group(['prefix'=>'ots'], function(){

	Route::get('add', 'ProjectsController@GetAdd');
	Route::post('add', 'ProjectsController@PostAdd');
	
	// Route::get('list', 'ProjectsController@GetList');

	// Route::group(['middleware'=>'AdminLogin'], function(){
	// 	Route::get('delete/{id}', 'ProjectsController@GetDelete');
	// 	Route::get('edit/{id}', 'ProjectsController@GetEdit');
	// 	Route::post('edit/{id}', 'ProjectsController@PostEdit');
	// });
});
