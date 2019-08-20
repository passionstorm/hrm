<?php

use App\Constants;
use Illuminate\Support\Facades\Route;

$roleAdmin = 'role:' . Constants::ROLE_ADMIN;
$roleMember = 'role:' . Constants::ROLE_MEMBER;
$roleManager = 'role:' . Constants::ROLE_ADMIN . ',' . Constants::ROLE_STAFF;

Route::get('test', function () {
    echo json_encode( vacationSpent((object)['start'=> '2019-08-18 08:00:00', 'end'=> '2019-08-18 17:00:00']) );
    echo '<hr>';
    // echo json_encode($arrShift);echo '<hr>';
});
function vacationSpent($vacationDays){
    $spent = new stdClass();
    $spent->time = 0;
    $spent->fullShift = 0;
    $spent->nonFullShift = 0;
    $arrStart = explode(' ', $vacationDays->start);
    $arrEnd = explode(' ', $vacationDays->end);
    $startDate = strtotime($arrStart[0]);
    $startTime = strtotime($arrStart[1]);
    $endDate = strtotime($arrEnd[0]);
    $endTime = strtotime($arrEnd[1]);
    if($endDate == $startDate){
        $middleDays = 0;
    }else{
        $middleDays = ($endDate - $startDate)/60/60/24 - 1;
    }
    $rawShift = DB::table('users')->find(Auth::user()->id)->shift;
    $rawArrShift = explode('-', $rawShift);
    $arrShift = [];
    foreach($rawArrShift as $ras){
        $sp = DB::table('session')->find($ras);
        array_push($arrShift, $sp->start, $sp->end);
    }
    $workTimesPerDay = 0;
    $amountShiftsPerDay = 0;
    for($y = 0; $y < count($arrShift); $y += 2){
        $startShift = strtotime($arrShift[$y]);
        $endShift = strtotime($arrShift[$y+1]);
        $workTimesPerDay += ($endShift - $startShift)/60;
        $amountShiftsPerDay++;
        if($startDate == $endDate){echo json_encode($middleDays);echo '<hr>';
            if($endTime <= $startShift){echo json_encode(-1);echo '<hr>';
                break;
            }elseif($endTime > $startShift && $endTime < $endShift){echo json_encode(-2);echo '<hr>';
                if($startTime <= $startShift){
                    $spent->time += ($endTime - $startShift)/60;
                    $spent->nonFullShift++;
                }elseif($startTime > $startShift){
                    $spent->time += ($endTime - $startTime)/60;
                    $spent->nonFullShift++;
                }
                break;
            }
        }

        if($startTime <= $startShift){echo json_encode(1);echo '<hr>';
            $spent->time += ($endShift - $startShift)/60;
            $spent->fullShift++;
        }elseif($startTime > $startShift && $startTime < $endShift){echo json_encode(2);echo '<hr>';
            $spent->time += ($endShift - $startTime)/60;
            $spent->nonFullShift++;
        }

        if($startDate == $endDate){
            continue;
        }elseif($endTime > $startShift && $endTime < $endShift){echo json_encode(3);echo '<hr>';
            $spent->time += ($endTime - $startShift)/60;
            $spent->nonFullShift++;
        }elseif($endTime >= $endShift){echo json_encode(4);echo '<hr>';
            $spent->time += ($endShift - $startShift)/60;
            $spent->fullShift++;
        }

    }
    $spent->time += $middleDays*$workTimesPerDay;
    $spent->fullShift += $middleDays*$amountShiftsPerDay;
    return $spent;
}


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