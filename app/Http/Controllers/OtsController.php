<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use DB;
use Constants;
use Validator;
use SebastianBergmann\Environment\Console;

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

   public function AddOTs()
   {
      $projects = DB::table('projects')->get();
      return view('ots.post', ['projects' => $projects]);
   }

   public function PostOT(Request $request)
   {
      if ($request->ajax()) {
         //validate
         $amount = count($request->project);
         $user_id = Auth::user()->id;
         $errorDates = [];
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
         for($i = 0; $i < $amount; $i++){
            if (strtotime($request->date[$i]) < strtotime('today')) {
               return response()->json([
                  'errorDates' => 'date'.$i,
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
         //check conflict in 'ot_detail' table
         for ($i = 0; $i < $amount; $i++) {
            $ot_id = $this->findOtId($request->date[$i], $user_id);
            if (count($ot_id) > 0) {
               $v = $ot_id[0]->id;
               $times = DB::table('ot_detail')->where('ot_id', $v)->select('time_start as start', 'time_end as end')->get();
               foreach ($times as $time) {
                  $time2 = (object) ['start' => $request->start[$i], 'end' => $request->end[$i]];
                  if ($this->checkConflictTime($time, $time2)) {
                     //response errors
                     return response()->json([
                        'existOT' => $i
                     ]);
                     //end-response errors
                  }
               }
            }
         }
         //end-check conflict in 'ot_detail' table
         //end-validate
         $ot_dates = array_unique($request->date);
         //save to 'ot' table
         foreach ($ot_dates as $ot_date) {
            //check exitst ot date of current user
            $existOT = $this->findOtId($ot_date, $user_id);
            //
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
         //
         //response success
         return response()->json([
            'success' => 'success',
         ]);
         //
      }
   }
}
