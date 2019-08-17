<?php

use App\Constants;
use Illuminate\Support\Facades\Route;

$roleAdmin = 'role:' . Constants::ROLE_ADMIN;
$roleMember = 'role:' . Constants::ROLE_MEMBER;
$roleManager = 'role:' . Constants::ROLE_ADMIN . ',' . Constants::ROLE_STAFF;

Route::get('test', function () {
    $rawArr = explode('-', DB::table('setting')->where('companyId', Auth::user()->companyId)->select('workTime')->get()[0]->workTime);
    $arr = [];
    foreach($rawArr as $ra){
        $d = DB::table('session')->find($arr);
        array_push($arr, $d->name);
        // array_push($arr, $d->name, $d->start, $d->end);
    }
    echo json_encode( $arr );
    echo '<hr>';
});


Route::get('/', function () {
    return view('welcome');
});

Route::get('login', 'UserController@GetLogin');
Route::post('login', 'UserController@PostLogin');
Route::get('logout', 'UserController@GetLogout');
Route::get('index', 'UserController@GetIndexPage')->name('dashboard')->middleware("login");


//users
Route::get('users/list', 'UserController@GetList')->middleware($roleManager);
Route::get('users/delete/{id}', 'UserController@DeleteUser')->middleware($roleAdmin);
Route::get('users/edit/{id?}', 'UserController@EditUser')->middleware($roleAdmin);
Route::post('users/edit/{id?}', 'UserController@PostUser')->middleware($roleAdmin);

//project
Route::get('projects/{id}/participants/add/', 'ProjectsController@AddParticipants')->middleware($roleManager);
Route::get('projects/participants/add/ajax', 'ProjectsController@AddParticipantsAjax')->middleware('AllowOnlyAjaxRequests');
Route::get('projects/participants/remove/ajax', 'ProjectsController@RemoveParticipantsAjax')->middleware('AllowOnlyAjaxRequests');
Route::get('projects/list', 'ProjectsController@GetList')->middleware($roleManager);
Route::get('projects/edit/{id?}', 'ProjectsController@EditProject')->middleware($roleAdmin);
Route::post('projects/edit/{id?}', 'ProjectsController@PostProject')->middleware($roleAdmin);
Route::get('projects/delete/{id}', 'ProjectsController@DeleteProject')->middleware($roleAdmin);

//ot
Route::get('ot/list', 'OtsController@GetList')->middleware("login");
Route::get('ot/list/ajax', 'OtsController@AjaxList')->middleware("AllowOnlyAjaxRequests");
Route::get('ot/post/{date}', 'OtsController@GetOTs')->middleware("login");
Route::post('ot/post', 'OtsController@PostOT')->middleware("login");

//quit time
Route::get('qt/post', 'QTController@GetQT')->middleware("login");
Route::post('qt/post', 'QTController@PostQT')->middleware("login");
Route::get('qt/list', 'QTController@GetList')->middleware("login");
Route::get('qt/list/pendding', 'QTController@GetListPendding')->middleware("login");
Route::get('qt/ajax/shortLeave', 'QTController@shortLeave')->middleware("AllowOnlyAjaxRequests");
Route::get('qt/ajax/searchByDate', 'QTController@SearchByDate')->middleware("AllowOnlyAjaxRequests");
Route::get('qt/ajax/handlingVacation', 'QTController@HandlingVacation')->middleware("AllowOnlyAjaxRequests");