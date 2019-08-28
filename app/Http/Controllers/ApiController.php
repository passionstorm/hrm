<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use stdClass;

class ApiController extends Controller
{
    public function HandlingVacation(Request $request){
        $QTController = new VacationController();
        $vacationDays = new stdClass();
        $vacationDays->start = $request->start;
        $vacationDays->end = $request->end;
        $spentTime  = $QTController->VacationSpent($vacationDays);
        return response()->json([
            'spent'=>$spentTime 
        ]);
    }

    public function AjaxList(Request $request)
    {
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

    public function AddParticipantsAjax(Request $request)
    {
        $p_id = $request->p_id;
        $p = DB::table('projects')->find($p_id)->participants;
        if (!$p) {
            $p = array();
        } else {
            $p = explode(',',$p);
        }
        array_push($p, $request->addValue);
        $p = implode(',',$p);
        DB::table('projects')->where('id', $p_id)->update([
            'participants' => $p
        ]);
        return response()->json([
            'added_id' => $request->addValue
        ]);
    }

    public function RemoveParticipantsAjax(Request $request)
    {
        $p_id = $request->p_id;
        $p = DB::table('projects')->find($p_id)->participants;
        $p = explode(',',$p);

        for($i = 0; $i<count($p); $i++){
            if( $p[$i] == $request->removeValue ){
                unset($p[$i]);
            } 
        }
        $p = implode(',',$p);
        DB::table('projects')->where('id', $p_id)->update([
            'participants' => $p
        ]);
        return response()->json([
            'removed_id' => $request->removeValue
        ]);
    }
}
