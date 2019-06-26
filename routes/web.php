<?php

use App\Constants;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('test', function () {
    echo Constants::COUNTRIES['vn'];

});

Route::get('login', 'UserController@GetLogin');
Route::post('login', 'UserController@PostLogin');
Route::get('logout', 'UserController@GetLogout');
Route::get('index', 'UserController@GetIndexPage');


Route::group(['prefix' => 'users'], function () {
    Route::get('list', 'UserController@GetList')->middleware('PreventMem');
    Route::group(['middleware' => 'AdminLogin'], function () {
        Route::get('delete/{id}', 'UserController@GetDeleteUser');
    });
    Route::get('post/{id?}', 'UserController@GetPost');
    Route::post('post/{id?}', 'UserController@PostPost');

});

Route::group(['prefix' => 'projects', 'middleware' => 'PreventMem'], function () {
    Route::get('list', 'ProjectsController@GetList');
    Route::group(['middleware' => 'AdminRole'], function () {
        Route::get('post/{id?}', 'ProjectsController@GetPost');
        Route::post('post/{id?}', 'ProjectsController@PostPost');
        Route::get('delete/{id}', 'ProjectsController@GetDelete');
    });
});

Route::group(['prefix'=>'ots'], function(){

	Route::get('add', 'OtsController@GetAdd');
	Route::post('add', 'OtsController@PostAdd');
	
	// Route::get('list', 'ProjectsController@GetList');

	// Route::group(['middleware'=>'AdminLogin'], function(){
	// 	Route::get('delete/{id}', 'ProjectsController@GetDelete');
	// 	Route::get('edit/{id}', 'ProjectsController@GetEdit');
	// 	Route::post('edit/{id}', 'ProjectsController@PostEdit');
	// });
});
