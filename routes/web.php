<?php

use App\Constants;
use Illuminate\Support\Facades\Route;


$roleAdmin = 'role:' . Constants::ROLE_ADMIN;
$roleMember = 'role:' . Constants::ROLE_MEMBER;
$roleManager = 'role:' . Constants::ROLE_ADMIN . ',' . Constants::ROLE_STAFF;



Route::get('test', function () {
    $year = '2019'; $month='07';$project = 0;
    $ots = DB::table('ot')->where('user_id', Auth::user()->id)->whereYear('ot_date', $year)->whereMonth('ot_date', $month)->select('ot_date as date', 'id', 'approved');
    if ($project == 0) {
        $ot_details = DB::table('ot_detail')->joinSub($ots, 'ots', function ($join) {
           $join->on('ot_detail.ot_id', '=', 'ots.id');
        })->join('projects', 'ot_detail.project_id', '=', 'projects.id')->select('ot_detail.id', 'ot_detail.time_start as start', 'ot_detail.time_end as end', 'projects.name', 'ots.date', 'ots.approved')->get();
     } else {
        $ot_details = DB::table('ot_detail')->where('project_id', $project)->joinSub($ots, 'ots', function ($join) {
           $join->on('ot_detail.ot_id', '=', 'ots.id');
        })->join('projects', 'ot_detail.project_id', '=', 'projects.id')->select('ot_detail.id', 'ot_detail.time_start as start', 'ot_detail.time_end as end', 'projects.name', 'ots.date', 'ots.approved')->get();
     }
    echo ($ot_details);
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
Route::get('projects/list', 'ProjectsController@GetList')->middleware($roleManager);
Route::get('projects/edit/{id?}', 'ProjectsController@EditProject')->middleware($roleAdmin);
Route::post('projects/edit/{id?}', 'ProjectsController@PostProject')->middleware($roleAdmin);
Route::get('projects/delete/{id}', 'ProjectsController@DeleteProject')->middleware($roleAdmin);

//ot
Route::get('ot/list', 'OtsController@GetList')->middleware("login");
Route::get('ot/list/ajax', 'OtsController@AjaxList')->middleware("login");
Route::get('ot/post/{id?}', 'OtsController@GetOTs')->middleware("login");
Route::post('ot/post', 'OtsController@PostOT')->middleware("login");
