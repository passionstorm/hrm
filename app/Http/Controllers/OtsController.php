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

   function sortByStartTime($array){
      for($i = 0; $i<count($array)-1; $i++){
         for($y = $i+1; $y<count($array); $y++){
             if( strtotime($array[$i]->start) > strtotime($array[$y]->start) ){
                 $v = $array[$i];$array[$i] = $array[$y];$array[$y] = $v;
             }
         }
     }
   }

   public function GetOTs($date)
   {
      $projects = DB::table('projects')->select('id', 'name')->get();

      $ot = DB::table('ot')->where([
         ['ot_date', $date],
         ['user_id', Auth::user()->id],
      ])->get();

      if ( count($ot) > 0) {
         $ot_ds = DB::table('ot_detail')->where('ot_id', $ot[0]->id)
         ->join('projects', 'projects.id', 'ot_detail.project_id')
         ->select('ot_detail.id','comment', 'time_start as start', 'time_end as end', 'project_id', 'projects.name')->get();
         $this->sortByStartTime($ot_ds);
         return view('ots.post', [
            'projects' => $projects, 
            'item' => $ot_ds, 
            'date' => $date,
            'approved' => $ot[0]->approved
            ]);
      }

      return view('ots.post', ['projects' => $projects, 'date' => $date]);
   }

   public function PostOT(Request $request)
   {
      if ($request->ajax()) {
         //validate
         $data = $request->data;
         $date = $request->date;
         $approved = $request->approved;
         $amount = count($data);
         $user_id = Auth::user()->id;

         //check user enter same post value
         if ($amount >= 2) {
            for ($i = 0; $i < $amount - 1; $i++) {
               for ($y = $i + 1; $y < $amount; $y++) {
                  if( substr($data[$i]['id'], 0, 4) == substr($data[$y]['id'], 0, 4) && $data[$i]['start'] == $data[$y]['start'] && $data[$i]['end'] == $data[$y]['end'] ){
                     return response()->json([
                        'samePosts' => [ $data[$i]['id'], $data[$y]['id'] ]
                     ]);
                  }
               }
            }
         }
         //end-check user enter same post value

         //check error time
         for ($i = 0; $i < $amount; $i++) {
            if (strtotime($data[$i]['start']) >= strtotime($data[$i]['end'])) {
               return response()->json([
                  'errorTimes' => [ $data[$i]['id'] ],
               ]);
            }
         }
         //end-check error time

         //check conflict ot post
         if ($amount > 1) {
            for ($i = 0; $i < $amount - 1; $i++) {
               for ($y = $i + 1; $y < $amount; $y++) {
                  if( substr($data[$i]['id'], 0, 4) == substr($data[$y]['id'], 0, 4) ){
                     $time1 = (object) [
                        'start' => $data[$i]['start'], 'end' => $data[$i]['end']
                     ];
                     $time2 = (object) [
                        'start' => $data[$y]['start'], 'end' => $data[$y]['end']
                     ];
                     if ($this->checkConflictTime($time1, $time2)) {
                        return response()->json([
                           'existOT' => [$data[$i]['id'], $data[$y]['id']]
                        ]);
                     }
                  }
               }
            }
         }
         //end-check conflict ot post

         //check conflict in ot_detail table
         for ($i = 0; $i < $amount-1; $i++) {
            for($y = $i + 1; $y < $amount; $y++){
               $time1 = (object) ['start' => $data[$i]['start'], 'end' => $data[$i]['end']];
               $time2 = (object) ['start' => $data[$y]['start'], 'end' => $data[$y]['end']];
               if ($this->checkConflictTime($time1, $time2)) {
                  return response()->json([
                     'existOT' => [ $data[$i]['id'], $data[$y]['id'] ]
                  ]);
               }
            }
         }
         //end-check conflict in ot_detail table

         //end-validate

         //save to 'ot' table
         //check exitst ot date of current user
         $existOT = $this->findOtId($date, $user_id);
         //end-check exitst ot date of current user
         if (count($existOT) == 0) {//case of add
            DB::table('ot')->insert(
               [
                  'user_id' => $user_id,  
                  'ot_date' => $date,
                  'weekend_flag' => $this->isWeekend($date),
                  'approved' => $approved,
                  'created_at' => Carbon::now(),
                  'created_by' => Auth::user()->username
               ]
            );
         }//end-case of add
         else {//case of update
            $eoID = $existOT[0]->id;
            DB::table('ot')->where('id', $eoID)->update(
               [
                  'approved' => $approved,
                  'updated_at' => Carbon::now(),
                  'updated_by' => Auth::user()->username
               ]
            );
         }//end-case of update
         //end-save to 'ot' table

         //save to 'ot_detail' table
         $ot_id = $this->findOtId($date, $user_id);
         DB::table('ot_detail')->where('ot_id', $ot_id[0]->id)->delete();
         for ($i = 0; $i < $amount; $i++) {
               DB::table('ot_detail')->insert(
                  [
                     'ot_id' => $ot_id[0]->id,
                     'time_start' => $data[$i]['start'],
                     'time_end' => $data[$i]['end'],
                     'project_id' => $data[$i]['project_id'],
                     'comment' => $data[$i]['comment'],
                     'created_at' => Carbon::now(),
                     'created_by' => Auth::user()->username,
                  ]
               );
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

}
