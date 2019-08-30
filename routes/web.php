<?php

use App\Constants;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('home');
});

Route::get('login', 'Auth\LoginController@login')->name('user.login.index');
Route::post('login', 'Auth\LoginController@postLogin')->name('user.login.post');
Route::get('logout', 'Auth\LoginController@logout')->name('user.login.logout');

Route::get('index', 'UserController@getIndexPage')->name('home')->middleware(Constants::AUTHORIZE_AUTH);

//users
Route::get('users/list', 'UserController@getList')->name('users.list')->middleware(Constants::AUTHORIZE_AUTH);
Route::get('users/delete/{id}', 'UserController@deleteUser')->name('users.delete')->middleware(Constants::AUTHORIZE_ADMIN);
Route::get('users/edit/{id?}', 'UserController@editUser')->name('users.edit')->middleware(Constants::AUTHORIZE_ADMIN);
Route::post('users/edit/{id?}', 'UserController@postUser')->name('users.post')->middleware(Constants::AUTHORIZE_ADMIN);

//project
Route::get('projects/{id}/participants/add/', 'ProjectsController@AddParticipants')->middleware(Constants::AUTHORIZE_MANAGER);
Route::get('projects/list', 'ProjectsController@GetList')->name('projects.list')->middleware(Constants::AUTHORIZE_MANAGER);
Route::get('projects/edit/{id?}', 'ProjectsController@EditProject')->name('projects.edit')->middleware(Constants::AUTHORIZE_ADMIN);
Route::post('projects/edit/{id?}', 'ProjectsController@PostProject')->name('projects.post')->middleware(Constants::AUTHORIZE_ADMIN);
Route::get('projects/delete/{id}', 'ProjectsController@DeleteProject')->name('projects.delete')->middleware(Constants::AUTHORIZE_ADMIN);

//ot
Route::get('ot/list', 'OtsController@GetList')->name('ot.list')->middleware(Constants::AUTHORIZE_AUTH);
Route::get('ot/post/{date}', 'OtsController@GetOTs')->name('ot.edit')->middleware(Constants::AUTHORIZE_AUTH);
Route::post('ot/post', 'OtsController@PostOT')->name('ot.post')->middleware(Constants::AUTHORIZE_AUTH);

//quit time
Route::get('vacation/post', 'VacationController@getVacation')->name('vacation.edit')->middleware(Constants::AUTHORIZE_AUTH);
Route::post('vacation/post', 'VacationController@postVacation')->name('vacation.post')->middleware(Constants::AUTHORIZE_AUTH);
Route::get('vacation/list', 'VacationController@getList')->name('vacation.list')->middleware(Constants::AUTHORIZE_AUTH);

//api
