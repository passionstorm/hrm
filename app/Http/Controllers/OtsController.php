<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use DB;
use Constants;
use Validator;
use SebastianBergmann\Environment\Console;
use ___PHPSTORM_HELPERS\object;

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
         if( !$request->ignoreConflictTime ){
            //check conflict in 'ot_detail' table
            for ($i = 0; $i < $amount; $i++) {
               $ot_id = $this->findOtId($request->date[$i], $user_id);
               if (count($ot_id) > 0) {
                  $v = $ot_id[0]->id;
                  if($request->id){
                     $times = DB::table('ot_detail')->where([
                        ['ot_id', $v],
                        ['id', '!=', $request->id],
                     ])->select('time_start as start', 'time_end as end')->get();
                  }else{
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
            if($request->id){
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
            else{
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
      $project_ids = [];
      $project_names = [];
      $ot_ids = DB::table('ot')->where('user_id', Auth::user()->id)->select('id')->get();
      foreach ($ot_ids as $ot_id) {
         $ids = DB::table('ot_detail')->where('ot_id', $ot_id->id)->select('project_id')->get();
         foreach ($ids as $x) {
            $id = $x->project_id;
            $name = DB::table('projects')->find($id)->name;
            array_push($project_ids, $id);
            array_push($project_names, $name);
         }
      }
      $project_ids = array_unique($project_ids);
      $project_names = array_unique($project_names);
      $projects = array_combine($project_ids, $project_names);
      return view('ots.list', ['projects' => $projects]);
   }

   public function AjaxList(Request $request)
   {
      if ($request->ajax()) {
         $project = $request->project;
         $year = $request->year;
         $month = $request->month;
         // Create appropriate data as required of request
         $items = [];
         $amount = 0;
         $ots = DB::table('ot')->where('user_id', Auth::user()->id)->whereYear('ot_date', $year)->whereMonth('ot_date', $month)->select('ot_date as date', 'id', 'approved')->get();
         foreach ($ots as $ot) {
            if ($project == 0) {
               $ots_detail = DB::table('ot_detail')->where('ot_id', $ot->id)->select('id', 'time_start as start', 'time_end as end', 'project_id', 'is_deleted')->get();
            } else {
               $ots_detail = DB::table('ot_detail')->where([
                  ['ot_id', $ot->id],
                  ['project_id', $project],
               ])->select('id', 'time_start as start', 'time_end as end', 'project_id', 'is_deleted')->get();
            }
            foreach ($ots_detail as $ot_detail) {
               if ($ot->approved == 0) {
                  $approved = 'No';
               } else {
                  $approved = 'Yes';
                  $amount += (strtotime($ot_detail->end) - strtotime($ot_detail->start)) / 3600;
               }
               $item = (object) [
                  'id' => $ot_detail->id,
                  'date' => $ot->date,
                  'start' => $ot_detail->start,
                  'end' => $ot_detail->end,
                  'project' => DB::table('projects')->find($ot_detail->project_id)->name,
                  'approved' => $approved,
                  'is_deleted' => $ot_detail->is_deleted,
               ];
               array_push($items, $item);
            }
         }
         //end-Create appropriate data as required of request

         return response()->json([
            'items' => $items,
            'amount' => $amount,
         ]);
      }
   }
}
