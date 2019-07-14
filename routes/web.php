<?php

use App\Constants;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Carbon;
// use DB;

$roleAdmin = 'role:' . Constants::ROLE_ADMIN;
$roleMember = 'role:' . Constants::ROLE_MEMBER;
$roleManager = 'role:' . Constants::ROLE_ADMIN . ',' . Constants::ROLE_STAFF;



Route::get('test', function () {
    function checkConflictTime($obj_time_1, $obj_time_2){
        $start1 = strtotime($obj_time_1->start);
        $end1 = strtotime($obj_time_1->end);
        $start2 = strtotime($obj_time_2->start);
        $end2 = strtotime($obj_time_2->end);
        if( ($start2 >= $start1 && $start2 < $end1) || ($start2 < $start1 && $end2 > $start1) ){
            return true;
        }
        return false;
    }
    $v = DB::table('ot_detail')->where('ot_id', 6)->select('time_start as start', 'time_end as end')->get();
    $o = (object) ['start' => '01:00', 'end' => '01:01'];
    echo var_dump($v[0]);
    echo '<br>';
    echo var_dump($o);
    $e = checkConflictTime($v[0], $o);
    echo '<br>';
    echo var_dump($e);
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
Route::get('ot/post', 'OtsController@AddOTs')->middleware("login");
Route::post('ot/post', 'OtsController@PostOT')->middleware("login");
