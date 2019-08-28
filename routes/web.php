<?php

use App\Constants;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('login', 'Auth\LoginController@login');
Route::post('login', 'Auth\LoginController@postLogin');
Route::get('logout', 'Auth\LoginController@logout');

Route::get('index', 'UserController@getIndexPage')->name('dashboard')->middleware(Constants::AUTHORIZE_AUTH);

//users
Route::get('users/list', 'UserController@getList')->middleware(Constants::AUTHORIZE_AUTH);
Route::get('users/delete/{id}', 'UserController@deleteUser')->middleware(Constants::AUTHORIZE_ADMIN);
Route::get('users/edit/{id?}', 'UserController@editUser')->middleware(Constants::AUTHORIZE_ADMIN);
Route::post('users/edit/{id?}', 'UserController@postUser')->middleware(Constants::AUTHORIZE_ADMIN);

//project
Route::get('projects/{id}/participants/add/', 'ProjectsController@AddParticipants')->middleware(Constants::AUTHORIZE_MANAGER);
Route::get('projects/list', 'ProjectsController@GetList')->middleware(Constants::AUTHORIZE_MANAGER);
Route::get('projects/edit/{id?}', 'ProjectsController@EditProject')->middleware(Constants::AUTHORIZE_ADMIN);
Route::post('projects/edit/{id?}', 'ProjectsController@PostProject')->middleware(Constants::AUTHORIZE_ADMIN);
Route::get('projects/delete/{id}', 'ProjectsController@DeleteProject')->middleware(Constants::AUTHORIZE_ADMIN);

//ot
Route::get('ot/list', 'OtsController@GetList')->middleware(Constants::AUTHORIZE_AUTH);
Route::get('ot/post/{date}', 'OtsController@GetOTs')->middleware(Constants::AUTHORIZE_AUTH);
Route::post('ot/post', 'OtsController@PostOT')->middleware(Constants::AUTHORIZE_AUTH);

//quit time
Route::get('vacation/post', 'VacationController@getVacation')->middleware(Constants::AUTHORIZE_AUTH);
Route::post('vacation/post', 'VacationController@postVacation')->middleware(Constants::AUTHORIZE_AUTH);
Route::get('vacation/list', 'VacationController@getList')->middleware(Constants::AUTHORIZE_AUTH);

//api
Route::get('projects/participants/add/ajax', 'ApiController@AddParticipantsAjax')->middleware(Constants::AUTHORIZE_AJAX_REQUEST);
Route::get('projects/participants/remove/ajax', 'ApiController@RemoveParticipantsAjax')->middleware(Constants::AUTHORIZE_AJAX_REQUEST);
Route::get('ot/list/ajax', 'ApiController@AjaxList')->middleware(Constants::AUTHORIZE_AJAX_REQUEST);
Route::get('qt/ajax/handlingVacation', 'ApiController@HandlingVacation')->middleware(Constants::AUTHORIZE_AJAX_REQUEST);