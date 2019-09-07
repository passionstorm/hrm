<?php

use App\Constants;
use Illuminate\Support\Facades\Route;


$roleAdmin = 'role:' . Constants::ROLE_ADMIN;
$roleMember = 'role:' . Constants::ROLE_MEMBER;
$roleManager = 'role:' . Constants::ROLE_ADMIN . ',' . Constants::ROLE_STAFF;

Route::get('test', function () {
    $user = Auth::user();
$companyName = DB::table('companies')->find($user->id);
    echo $companyName;echo '<hr>';
    
    // echo var_dump( $history );echo '<hr>';
    // echo json_encode($shifts);echo '<hr>';
});
Route::get('/', function () {
    return view('welcome');
});

Route::get('register', function () {
    return view('register');
});

Route::get('login', 'UserController@GetLogin');
Route::post('login', 'UserController@PostLogin');
Route::get('logout', 'UserController@GetLogout');
Route::get('index', 'UserController@GetIndexPage')->name('dashboard')->middleware("login");

Route::get('index', 'UserController@getIndexPage')->name('dashboard')->middleware(Constants::AUTHORIZE_AUTH);

//users
Route::get('users/list', 'UserController@GetList')->middleware($roleManager);
Route::get('users/delete/{id}', 'UserController@DeleteUser')->middleware($roleAdmin);
Route::get('users/edit/{id?}', 'UserController@EditUser')->middleware($roleAdmin);
Route::post('users/edit/{id?}', 'UserController@PostUser');

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