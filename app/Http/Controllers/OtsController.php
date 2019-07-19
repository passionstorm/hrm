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
                     return response()->json([
                        'samePosts' => [$i, $y]
                     ]);
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

         //check conflict ot post
         if ($amount > 1) {
            for ($i = 0; $i < $amount - 1; $i++) {
               for ($y = $i + 1; $y < $amount; $y++) {
                  if ($request->date[$i] ==  $request->date[$y]) {
                     $time1 = (object) [
                        'start' => $request->start[$i], 'end' => $request->end[$i]
                     ];
                     $time2 = (object) [
                        'start' => $request->start[$y], 'end' => $request->end[$y]
                     ];
                     if ($this->checkConflictTime($time1, $time2)) {
                        return response()->json([
                           'existOT' => [$i, $y]
                        ]);
                     }
                  }
               }
            }
         }
         //end-check conflict ot post

         //check conflict in ot_detail table
         if (!$request->ignoreConflictTime) {
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
                           'existOT' => [$i]
                        ]);
                     }
                  }
               }
            }
         }
         //end-check conflict in ot_detail table

         //end-validate

         //save to 'ot' table
         $ot_dates = array_unique($request->date);
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
         //end-save to 'ot' table

         //save to 'ot_detail' table
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
         //end-save to 'ot_detail' table

         //response success
         return response()->json([
            'success' => 'success',
         ]);
         //end-response success
      }
   }

   public function GetList()
   {
      //Advanced query join
      $projects = DB::table('ot')
         ->where('user_id', Auth::user()->id)
         ->join('ot_detail', function ($join) {
            $join->on('ot.id', 'ot_detail.ot_id');
         })
         ->join('projects', function ($join) {
            $join->on('ot_detail.project_id', 'projects.id');
         })
         ->select('projects.name', 'projects.id')->distinct()->get();
      //end-Advanced query join

      $month = date('m');
      $yearMonth = date('Y-m');
      $today = date('d');
      $ots = DB::table('ot')
         ->where('user_id', Auth::user()->id)->whereMonth('ot_date', $month)
         ->join('ot_detail', 'ot.id', 'ot_detail.ot_id')
         ->select('ot.ot_date', 'ot_detail.time_start', 'ot_detail.time_end', 'ot.approved')->get();
      foreach ($ots as $i) {
         $i->startToEnd = $i->time_start . '-' . $i->time_end;
         $i->ot_t = number_format((strtotime($i->time_end) - strtotime($i->time_start)) / 3600,1);
         unset($i->time_start, $i->time_end);
      }

      return view('ots.list', [
         'yearMonth' => $yearMonth,
         'projects' => $projects,
         'today' => $today,
         'ots' => $ots
      ]);
   }

   public function AjaxList(Request $request)
   {
      if ($request->ajax()) {
         $project = $request->project;
         $year = $request->year;
         $month = $request->month;
         if(date('Y-m') == ($year . '-' . $month)){
            $daysOfMonth = date('d');
         }else{
            $daysOfMonth = date('t', strtotime($year . '-' . $month));
         }
         // Create appropriate data as required of request
         //Advanced query join
         $ots = DB::table('ot')
            ->where('user_id', Auth::user()->id)->whereYear('ot_date', $year)->whereMonth('ot_date', $month)
            ->join('ot_detail', function ($join) use ($project) {
               if ($project == 0) {
                  $join->on('ot.id', '=', 'ot_detail.ot_id');
               } else {
                  $join->on('ot.id', '=', 'ot_detail.ot_id')->where('ot_detail.project_id', $project);
               }
            })
            ->select('ot.ot_date', 'ot_detail.time_start', 'ot_detail.time_end', 'ot.approved')->get();
         //end-Advanced query join
         foreach ($ots as $i) {
            $i->startToEnd = $i->time_start . '-' . $i->time_end;
            $i->ot_t = number_format((strtotime($i->time_end) - strtotime($i->time_start)) / 3600, 1);
            unset($i->time_start, $i->time_end);
         }
         //end-Create appropriate data as required of request
         return response()->json([
            'daysOfMonth' => $daysOfMonth,
            'ots' => $ots
         ]);
      }
   }

   public function intermediate($date)
   {
      $ot_id = DB::table('ot')->where([
         ['ot_date', $date],
         ['user_id', Auth::user()->id],
      ])->select('id')->get();
      if (count($ot_id) == 0) {
         return redirect()->route('ot/post', ['date' => $date]);
      } else {
         $otDetailIds = DB::table('ot_detail')->where('ot_id', $ot_id[0]->id)->select('id')->get();
         return redirect()->route('ot/post', ['id' => $otDetailIds]);
      }
   }
}
