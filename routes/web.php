<?php

use App\Constants;
use Illuminate\Support\Facades\Route;

$roleAdmin = 'role:' . Constants::ROLE_ADMIN;
$roleMember = 'role:' . Constants::ROLE_MEMBER;
$roleManager = 'role:' . Constants::ROLE_ADMIN . ',' . Constants::ROLE_STAFF;

Route::get('test', function () {
    $data = [
        (object) [
          'id' => '1',
          'start' => '1',
          'end' => '1',
          'project_id' => '1',
          'name' => '1',
          'comment' => '1',
        ],
        (object) [
          'id' => '2',
          'start' => '2',
          'end' => '2',
          'project_id' => '2',
          'name' => '2',
          'comment' => '2',
        ]
  ];
    echo json_encode($data);
    echo '<hr>';
});


Route::get('/', function () {
    return view('layout.index');
})->middleware("login");

Route::get('login', 'UserController@GetLogin');
Route::post('login', 'UserController@PostLogin');
Route::get('logout', 'UserController@GetLogout');
Route::get('index', 'UserController@GetIndexPage')->name('dashboard');


//users
Route::get('users/list', 'UserController@GetList')->middleware($roleManager);
Route::get('users/delete/{id}', 'UserController@DeleteUser')->middleware($roleAdmin);
Route::get('users/edit/{id?}', 'UserController@EditUser')->middleware($roleAdmin);
Route::post('users/edit/{id?}', 'UserController@PostUser')->middleware($roleAdmin);

//project
Route::get('projects/{id}/participants/add/', 'ProjectsController@AddParticipants')->middleware($roleManager);
Route::get('projects/participants/add/ajax', 'ProjectsController@AddParticipantsAjax')->middleware($roleManager);
Route::get('projects/participants/remove/ajax', 'ProjectsController@RemoveParticipantsAjax')->middleware($roleManager);
Route::get('projects/list', 'ProjectsController@GetList')->middleware($roleManager);
Route::get('projects/edit/{id?}', 'ProjectsController@EditProject')->middleware($roleAdmin);
Route::post('projects/edit/{id?}', 'ProjectsController@PostProject')->middleware($roleAdmin);
Route::get('projects/delete/{id}', 'ProjectsController@DeleteProject')->middleware($roleAdmin);

//ot
Route::get('ot/list', 'OtsController@GetList')->middleware("login");
Route::get('ot/list/ajax', 'OtsController@AjaxList')->middleware("login");
Route::get('ot/post/{date}', 'OtsController@GetOTs')->middleware("login");
Route::post('ot/post', 'OtsController@PostOT')->middleware("login");
Route::get('ot/intermediate/{date}', 'OtsController@intermediate')->middleware("login");
