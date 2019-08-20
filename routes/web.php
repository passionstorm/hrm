<?php
use Carbon\Carbon;
use App\Constants;
use Illuminate\Support\Facades\Route;

$roleAdmin = 'role:' . Constants::ROLE_ADMIN;
$roleMember = 'role:' . Constants::ROLE_MEMBER;
$roleManager = 'role:' . Constants::ROLE_ADMIN . ',' . Constants::ROLE_STAFF;

Route::get('test', function () {
    $history = DB::table('vacations')->where('user_id', Auth::user()->id)->select('start', 'end', 'spent', 'is_approved', 'created_at', 'updated_at' )->get()->all();
    $sortedHistory = descSoftByUpdatedTime($history);
    // echo json_encode( $history );echo '<hr>';
    // echo var_dump( $history );echo '<hr>';
    echo json_encode($sortedHistory);echo '<hr>';
});
function descSoftByUpdatedTime($rawHistory){
    $isContinue = true;
    while($isContinue){
        $isContinue = false;
        for($i = 0; $i<count($rawHistory)-1; $i++){
            $start1 = $rawHistory[$i]->created_at;
            $start2 = $rawHistory[$i+1]->created_at;
            if($rawHistory[$i]->updated_at){
                $start1 = $rawHistory[$i]->updated_at;
            }
            if($rawHistory[$i+1]->updated_at){
                $start2 = $rawHistory[$i+1]->updated_at;
            }
            if( strtotime($start1) < strtotime($start2) ){
                $isContinue = true;
                $sp = $rawHistory[$i];
                $rawHistory[$i] = $rawHistory[$i+1];
                $rawHistory[$i+1] = $sp;
            }
        }
    }
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