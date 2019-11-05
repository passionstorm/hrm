<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Constants;
use stdClass;

class QTController extends Controller
{
    public function GetQT(){
        $user = Auth::user();
        $setting = DB::table('companies')->find($user->company_id);
        $idShiftList = explode('.', DB::table('users')->find($user->id)->shift);
        $shifts = DB::table('shifts')->whereIn('id', $idShiftList)->orderBy('start', 'asc')->get();
        $dynamicReason = DB::table('reasons')->where('company_id', $user->company_id)->select('reason', 'id')->get();
        $vacation = $setting->vacation_per_year;
        $vList = DB::table('vacations')->where([
            ['is_approved', Constants::APPROVED_VACATION],
            ['user_id', Auth::user()->id],
        ])->select('start', 'end')->get();
        $spent = 0;
        foreach($vList as $v){
            $spent += $this->VacationSpent((object)[
                'start'=>$v->start,
                'end'=>$v->end
            ]);
        }
        $time_remaining = $vacation - $spent;
        return view('qt.post', [
            'setting'=>$setting,
            'shifts'=>$shifts,
            'dynamicReason'=>$dynamicReason,
            'time_remaining'=>$time_remaining,
            'vacation'=>$vacation,
        ]);
    }

    public function GetList(){
        $user = Auth::user();
        $vacation = DB::table('companies')->find($user->company_id)->vacation_per_year;
        $vList = DB::table('vacations')->where([
            ['is_approved', '!=', Constants::REJECTED_VACATION],
            ['user_id', Auth::user()->id],
        ])->select('start', 'end', 'is_approved')->get();
        $aSpent = 0;
        $eSpent = 0;
        foreach($vList as $v){
            if($v->is_approved == Constants::APPROVED_VACATION){
                $aSpent += $this->VacationSpent((object)[
                    'start'=>$v->start,
                    'end'=>$v->end
                ]);
            }
            $eSpent += $this->VacationSpent((object)[
                'start'=>$v->start,
                'end'=>$v->end
            ]);
        }
        $aTimeRemaining = $vacation - $aSpent;
        $eTimeRemaining = $vacation - $eSpent;
        $history = DB::table('vacations')->where('user_id', Auth::user()->id)->orderBy('updated_at', 'desc')->select('start', 'end', 'is_approved')->get();
        return view('qt.list',[
            'aTimeRemaining'=>$aTimeRemaining,
            'eTimeRemaining'=>$eTimeRemaining,
            'vacation'=>$vacation,
            'history'=>$history,
            'today'=>date('Y-m-d'),
        ]);
    }

    public function PostQT(Request $request){
        $userId = Auth::user()->id;
        $sDate = $request->startDate;
        $eDate = $request->endDate;
        $sTime = $request->startTime.':00';
        $eTime = $request->endTime.':00';
        $type = $request->type;
        $comment = $request->comment;
        $start = $sDate.' '.$sTime;
        $end = $eDate.' '.$eTime;
        $now = date("Y-m-d H:i:s");
        DB::table('vacations')->insert([
            'user_id'=>$userId,
            'start'=>$start,
            'end'=>$end,
            'comment'=>$comment,
            'is_approved'=>Constants::PENDDING_VACATION,
            'type'=>$type,
            'created_at'=>$now,
            'updated_at'=>$now,
            'created_by'=>$userId,
        ]);
        return redirect('qt/list');
    }

    /**
     * count spent(hours) in vacation days
     * @param object 
     * @return number
     */
    function VacationSpent($vacationDays){
        $arrStart = explode(' ', $vacationDays->start);
        $arrEnd = explode(' ', $vacationDays->end);
        $startDate = strtotime($arrStart[0]);
        $startTime = strtotime($arrStart[1]);
        $endDate = strtotime($arrEnd[0]);
        $endTime = strtotime($arrEnd[1]);
        $middleDays = max(0, ($endDate - $startDate)/3600/24 - 1);
        $dayWorkTime = $this->getDayWorkTime();
        $spent = $middleDays*$dayWorkTime;
        $shifts = $this->getTimeShifts();
        if($startDate == $endDate){
            $spent += $this->_getSpentTimeSameDate($shifts, $startTime, $endTime);
        }else{
            $spent += $this->_getSpentTimeDiffDate($shifts, $startTime, $endTime, $dayWorkTime*3600);
        }
        return $spent;
    }

    function _getSpentTimeSameDate($shifts, $startTime, $endTime){
        $spentTime = 0;
        $spentShift = 0;
        foreach($shifts as $shift){
            $startShift = strtotime($shift['start']);
            $endShift = strtotime($shift['end']);
            if($startShift <= $endTime && $endTime <= $endShift){
                $spentTime += $endTime - $startShift + $spentShift;
            }
            if($startShift <= $startTime && $startTime <= $endShift){
                $spentTime -= $startTime - $startShift + $spentShift;
            }
            $spentShift += $shift['spent']*3600;
        }
        return $spentTime/3600;//hours unit
    }

    function _getSpentTimeDiffDate($shifts, $startTime, $endTime, $dayWorkTime){
        $spentTime = 0;
        $spentShift = 0;
        foreach($shifts as $shift){
            $startShift = strtotime($shift['start']);
            $endShift = strtotime($shift['end']);
            if($startShift <= $startTime && $startTime <= $endShift){
                $spentTime += $dayWorkTime - ($startTime - $startShift) - $spentShift;
            }
            if($startShift <= $endTime && $endTime <= $endShift){
                $spentTime += $endTime - $startShift + $spentShift;
            }
            $spentShift += $shift['spent']*3600;
        }
        return $spentTime/3600;//hours unit
    }

    /**
     * create list shift inlude start, end, spent(hour) for current user
     * @return array 
     */
    function getTimeShifts(){
        $idShiftList = explode( '.', DB::table('users')->find(Auth::user()->id)->shift );
        $shifts = DB::table('shifts')->whereIn('id', $idShiftList)->get()->all();
        return array_map(function($shift){
            return [
                'start' => $shift->start,
                'end' => $shift->end,
                'spent' => (strtotime($shift->end) - strtotime($shift->start))/3600,
            ];
        }, $shifts);
    }

    /**
     * calculate dayWorkTime(hour) for current user
     * @return float 
     */    
    function getDayWorkTime(){
        $dayWorkTime = 0;
        foreach( $this->getTimeShifts() as $s ){
            $dayWorkTime += $s['spent'];
        }
        return $dayWorkTime;
    }

}
