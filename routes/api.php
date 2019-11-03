<?php

use App\Constants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('projects/member/add', 'ApiController@AddParticipantsAjax')->name('api.projects.member.add');
Route::get('projects/member/del', 'ApiController@RemoveParticipantsAjax')->name('api.projects.member.del');
Route::get('ot/list', 'ApiController@AjaxList')->name('api.ot.list');
Route::get('qt/handlingVacation', 'ApiController@HandlingVacation')->name('api.ot.calculate');