<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use DB;

class OtsController extends Controller
{
   function isWeekend($date)
   {
      return (date('N', strtotime($date)) >= 6);
   }

   function findOtId($ot_date, $user_id)
   {
      return DB::table('ot')->where([
         ['ot_date', $ot_date],
         ['user_id', $user_id],
      ])->select('id')->get();
   }

   function checkConflictTime($obj_time_1, $obj_time_2)
   {
      $start1 = strtotime($obj_time_1->start);
      $end1 = strtotime($obj_time_1->end);
      $start2 = strtotime($obj_time_2->start);
      $end2 = strtotime($obj_time_2->end);
      if (($start2 >= $start1 && $start2 < $end1) || ($start2 < $start1 && $end2 > $start1)) {
         return true;
      }
      return false;
   }

   public function GetOTs($id = null)
   {
      $projects = DB::table('projects')->select('id', 'name')->get();
      //Edit view
      if ($id) {
         $ot_d = DB::table('ot_detail')->where('id', $id)->select('comment', 'ot_id', 'time_start as start', 'time_end as end', 'project_id', 'comment')->get();
         //Prevent edit ot other people
         if (DB::table('ot')->find($ot_d[0]->ot_id)->user_id != Auth::user()->id) {
            abort(404);
         }
         //end-Prevent edit ot other people
         $date = DB::table('ot')->find($ot_d[0]->ot_id)->ot_date;
         $item = (object) [
            'date' => $date,
            'start' => $ot_d[0]->start,
            'end' => $ot_d[0]->end,
            'project_id' => $ot_d[0]->project_id,
            'comment' => $ot_d[0]->comment,
         ];
         return view('ots.post', ['projects' => $projects, 'item' => $item]);
      }
      //end-Edit view
      //Create view
      return view('ots.post', ['projects' => $projects]);
      //end-Create view
   }

   public function PostOT(Request $request)
   {
      if ($request->ajax()) {
         //validate
         $amount = count($request->project);
         $user_id = Auth::user()->id;
         //check user enter same post value
         if ($amount >= 2) {
            for ($i = 0; $i < $amount - 1; $i++) {
               for ($y = $i + 1; $y < $amount; $y++) {
                  if ($request->date[$i] == $request->date[$y] && $request->start[$i] == $request->start[$y] && $request->end[$i] == $request->end[$y]) {
                     //response errors
                     return response()->json([
                        'samePosts' => [$i, $y]
                     ]);
                     //end-response errors
                  }
               }
            }
         }
         //end-check user enter same post value
         //check error date
         for ($i = 0; $i < $amount; $i++) {
            if (strtotime($request->date[$i]) < strtotime('today')) {
               return response()->json([
                  'errorDates' => 'date' . $i,
               ]);
            }
         }
         //end-check error date
         //check error time
         $errorTimes = [];
         for ($i = 0; $i < $amount; $i++) {
            if (strtotime($request->start[$i]) >= strtotime($request->end[$i])) {
               return response()->json([
                  'errorTimes' => ['start' . $i, 'end' . $i],
               ]);
            }
         }
         //end-check error time
         if (!$request->ignoreConflictTime) {
            //check conflict in 'ot_detail' table
            for ($i = 0; $i < $amount; $i++) {
               $ot_id = $this->findOtId($request->date[$i], $user_id);
               if (count($ot_id) > 0) {
                  $v = $ot_id[0]->id;
                  if ($request->id) {
                     $times = DB::table('ot_detail')->where([
                        ['ot_id', $v],
                        ['id', '!=', $request->id],
                     ])->select('time_start as start', 'time_end as end')->get();
                  } else {
                     $times = DB::table('ot_detail')->where('ot_id', $v)->select('time_start as start', 'time_end as end')->get();
                  }
                  foreach ($times as $time) {
                     $time2 = (object) ['start' => $request->start[$i], 'end' => $request->end[$i]];
                     if ($this->checkConflictTime($time, $time2)) {
                        return response()->json([
                           'existOT' => $i
                        ]);
                     }
                  }
               }
            }
            //end-check conflict in 'ot_detail' table
         }
         //end-validate
         $ot_dates = array_unique($request->date);
         //save to 'ot' table
         foreach ($ot_dates as $ot_date) {
            //check exitst ot date of current user
            $existOT = $this->findOtId($ot_date, $user_id);
            //end-check exitst ot date of current user
            //create
            if (count($existOT) == 0) {
               DB::table('ot')->insert(
                  [
                     'user_id' => $user_id,
                     'ot_date' => $ot_date,
                     'weekend_flag' => $this->isWeekend($ot_date),
                     'created_at' => Carbon::now(),
                     'created_by' => Auth::user()->username
                  ]
               );
            }
            //
            //update
            else {
               $eoID = $existOT[0]->id;
               DB::table('ot')->where('id', $eoID)->update(
                  [
                     'updated_at' => Carbon::now(),
                     'updated_by' => Auth::user()->username
                  ]
               );
            }
            //
         }
         //
         // save to 'ot_detail' table
         $projects = $request->project;
         for ($i = 0; $i < count($projects); $i++) {
            $ot_id = $this->findOtId($request->date[$i], $user_id);
            //Edit ot detail
            if ($request->id) {
               DB::table('ot_detail')->where('id', $request->id)->update(
                  [
                     'ot_id' => $ot_id[0]->id,
                     'time_start' => $request->start[$i],
                     'time_end' => $request->end[$i],
                     'project_id' => $request->project[$i],
                     'comment' => $request->comment[$i],
                     'updated_at' => Carbon::now(),
                     'updated_by' => Auth::user()->username,
                  ]
               );
            }
            //end-Edit ot detail
            //Add ot detail
            else {
               DB::table('ot_detail')->insert(
                  [
                     'ot_id' => $ot_id[0]->id,
                     'time_start' => $request->start[$i],
                     'time_end' => $request->end[$i],
                     'project_id' => $request->project[$i],
                     'comment' => $request->comment[$i],
                     'created_at' => Carbon::now(),
                     'created_by' => Auth::user()->username,
                  ]
               );
            }
            //end-Add ot detail
         }
         //
         //response success
         return response()->json([
            'success' => 'success',
         ]);
      }
   }

   public function GetList()
   {
      //query jojn
      $ot_ids = DB::table('ot')->where('user_id', Auth::user()->id)->select('id');
      $project_ids = DB::table('ot_detail')->joinSub($ot_ids, 'ot_ids', function ($join) {
         $join->on('ot_detail.ot_id', '=', 'ot_ids.id');
      })->select('project_id')->groupBy('project_id');
      $projects = DB::table('projects')->joinSub($project_ids, 'project_ids', function ($join) {
         $join->on('project_ids.project_id', '=', 'projects.id');
      })->select('name', 'id')->get();
      //end-query jojn
      return view('ots.list', ['projects' => $projects]);
   }

   public function AjaxList(Request $request)
   {
      if ($request->ajax()) {
         $project = $request->project;
         $year = $request->year;
         $month = $request->month;
         // Create appropriate data as required of request
         $amount = 0;
         $ots = DB::table('ot')->where('user_id', Auth::user()->id)->whereYear('ot_date', $year)->whereMonth('ot_date', $month)->select('ot_date as date', 'id', 'approved');
         if ($project == 0) {
            //query jojn
            $ot_details = DB::table('ot_detail')->joinSub($ots, 'ots', function ($join) {
               $join->on('ot_detail.ot_id', '=', 'ots.id');
            })->join('projects', 'ot_detail.project_id', '=', 'projects.id')->select('ot_detail.id', 'ot_detail.time_start as start', 'ot_detail.time_end as end', 'projects.name as project_name', 'ots.date', 'ots.approved')->get();
            //end-query jojn
         } else {
            //query jojn
            $ot_details = DB::table('ot_detail')->where('project_id', $project)->joinSub($ots, 'ots', function ($join) {
               $join->on('ot_detail.ot_id', '=', 'ots.id');
            })->join('projects', 'ot_detail.project_id', '=', 'projects.id')->select('ot_detail.id', 'ot_detail.time_start as start', 'ot_detail.time_end as end', 'projects.name as project_name', 'ots.date', 'ots.approved')->get();
            //end-query jojn
         }
         foreach($ot_details as $ot_detail){
            if ($ot_detail->approved != 0) {
               $amount += (strtotime($ot_detail->get()->end) - strtotime($ot_detail->get()->start)) / 3600;
            }
         }
         //end-Create appropriate data as required of request

         return response()->json([
            'items' => $ot_details,
            'amount' => $amount,
         ]);
      }
   }
}
