<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Constants;
use stdClass;

class QTController extends Controller
{
    public function GetQT(){
        $setting = DB::table('setting')->select('vacation', 'workTime', 'shortLeave', 'minTimeForHandling')->get()[0];
        return view('qt.post', ['setting'=>$setting]);
    }

    public function GetList(){
        $vacation = DB::table('setting')->select('vacation')->get()[0]->vacation;
        $spent = DB::table('vacations')->where([
            ['is_approved', Constants::APPROVED_VACATION],
            ['user_id', Auth::user()->id],
        ])->sum('spent');
        $time_remaining = $vacation - $spent;
        DB::table('users')->where('id', Auth::user()->id)->update([
            'time_remaining'=>$time_remaining
        ]);
        $history = DB::table('vacations')->where([
            ['user_id', Auth::user()->id],
            ['is_approved', '!=', Constants::REJECTED_VACATION],
        ])->select('start', 'end', 'spent', 'type', 'updated_at', 'created_at')->get();
        return view('qt.list',[
            'time_remaining'=>$time_remaining,
            'vacation'=>$vacation,
            'history'=>$history,
            'today'=>date('Y-m-d'),
        ]);
    }

    public function GetListPendding(){
        $pendding = DB::table('vacations')->where([
            ['is_approved', Constants::PENDDING_VACATION],
            ['user_id', Auth::user()->id],
        ])->select('start', 'end', 'spent', 'type')->orderBy('start', 'asc')->get();
        return view('qt.listPendding',[
            'pendding'=>$pendding,
        ]);
    }

    public function PostQT(Request $request){
        $userId = Auth::user()->id;
        if ($request->LEDate) {
            $date = $request->LEDate;
            $session = $request->session;
            $type = $request->type;
            $spent = explode(' ', $request->time)[0];
            if($request->rSelect == 0){
                $comment = '0-'.$request->comment;
            }else{
                $comment = $request->rSelect;
            }
            $rawArr = explode('-', DB::table('setting')->where('companyId', Auth::user()->companyId)->select('workTime')->get()[0]->workTime);
            $arr = [];
            foreach($rawArr as $ra){
                $d = DB::table('session')->find($ra);
                array_push($arr, $d->name, $d->start, $d->end);
            }
            for($i = 0; $i < count($arr); $i++){
                if($arr[$i] == $session){
                    $startS = str_replace('h',':', $arr[$i+1]);
                    $endS = str_replace('h',':', $arr[$i+2]);
                    break;
                }
            }
            if($type == Constants::EARLY_VACATION){
                $start = $date.' '.date("H:i:s", strtotime($endS) - $spent*60);
                $end = $date.' '.$endS;
            }elseif($type == Constants::LATE_VACATION){
                $start = $date.' '.$startS;
                $end = $date.' '.date("H:i:s", strtotime($startS) + $spent*60);
            }
            DB::table('vacations')->insert([
                'user_id'=>$userId,
                'start'=>$start,
                'end'=>$end,
                'spent'=>$spent,
                'comment'=>$comment,
                'is_approved'=>Constants::PENDDING_VACATION,
                'type'=>$type,
                'created_at'=>date("Y-m-d H:i:s"),
                'created_by'=>$userId,
            ]);
        }elseif($request->dayForOut){
            $date = $request->dayForOut;
            if($request->rSelect == 0){
                $comment = '0-'.$request->comment;
            }else{
                $comment = $request->rSelect;
            }
            $start = $date.' '.$request->start.':00';
            $end = $date.' '.$request->end.':00';
            $spent = (strtotime($end) - strtotime($start))/60;
            DB::table('vacations')->insert([
                'user_id'=>$userId,
                'start'=>$start,
                'end'=>$end,
                'spent'=>$spent,
                'comment'=>$comment,
                'is_approved'=>Constants::PENDDING_VACATION,
                'type'=>Constants::OUT_VACATION,
                'created_at'=>date("Y-m-d H:i:s"),
                'created_by'=>$userId,
            ]);
        }elseif($request->startD){
            $start =  $request->startD.' '.$request->vStartTime.':00';
            $end =  $request->endD.' '.$request->vEndTime.':00';
            if($request->rSelect == 0){
                $comment = '0-'.$request->comment;
            }else{
                $comment = $request->rSelect;
            }
            $spentObj = $this->vacationSpent((object)[
                'start'=>$start,
                'end'=>$end,
            ]);
            DB::table('vacations')->insert([
                'user_id'=>$userId,
                'start'=>$start,
                'end'=>$end,
                'spent'=>$spentObj->time,
                'comment'=>$comment,
                'type'=>Constants::OFF_VACATION,
                'is_approved'=>Constants::PENDDING_VACATION,
                'created_at'=>date("Y-m-d H:i:s"),
                'created_by'=>$userId,
            ]);
        }
        return redirect('qt/post');
    }

    public function SearchByDate(Request $request){
        $rawHistory = DB::table('vacations')->where([
            ['user_id', Auth::user()->id],
            ['is_approved', Constants::APPROVED_VACATION],
        ])->select('start', 'end', 'spent', 'type')->get();
        $history = $this->descSoftHistory(  $this->filterHistory($rawHistory, $request->date) );
        return response()->json([
            'data'=>$history
        ]);
    }

    public function HandlingVacation(Request $request){
        $vacationDays = new stdClass();
        $vacationDays->start = $request->start;
        $vacationDays->end = $request->end;
        $spentObj  = $this->vacationSpent($vacationDays);
        return response()->json([
            'spent'=>$spentObj 
        ]);
    }

    //not necessary for current template//filter history by date
    function filterHistory($rawHistory, $searchDate){
        $filterHistory = [];
        foreach($rawHistory as $i){
            if($i->type == Constants::OFF_VACATION){
                $arrXVacation = $this->vacationExplode($i);
                foreach($arrXVacation as $axv){
                    if( explode(' ', $axv->start)[0] == $searchDate ){
                        array_push($filterHistory, $axv);
                    }
                }
            }else{
                if( explode(' ', $i->start)[0] == $searchDate ){
                    array_push($filterHistory, $i);
                }
            }
        }
        return $filterHistory;
    }
    //end-filter history by date

    //soft desc history
    function descSoftHistory($rawHistory){
        $isContinue = true;
        while($isContinue){
            $isContinue = false;
            for($i = 0; $i<count($rawHistory)-1; $i++){
                if( strtotime($rawHistory[$i]->start) < strtotime($rawHistory[$i+1]->start) ){
                    $isContinue = true;
                    $sp = $rawHistory[$i];
                    $rawHistory[$i] = $rawHistory[$i+1];
                    $rawHistory[$i+1] = $sp;
                }
            }
        }
        return $rawHistory;
    }
    //end-soft desc history

    //soft desc history by updated time
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

    //end-soft desc history by updated time


    function getTimeShifts()
    {
        $rawShift = DB::table('users')->find(Auth::user()->id)->shift;
        $listShiftId = explode('-', $rawShift);
        $shifts = DB::table('session')->whereIn('id', $listShiftId)->get()->toArray();
        return array_map(function ($shift) {
            $shiftStart = Carbon::parse($shift->start);
            $shiftEnd = Carbon::parse($shift->end);
            $start = $shiftStart->hour + $shiftStart->minute / 60; //hour unit
            $end = $shiftEnd->hour + $shiftEnd->minute / 60; //hour unit
            $spent = $end - $start;
            return [
                'start' => $start,
                'end' => $end,
                'spent' => $spent
            ];
        }, $shifts);
    }


    function _getSpentTimeSameDate($listShift, $startTime, $endTime)
    {
        $spentTime = 0;
        $spentShift = 0;
        foreach ($listShift as $shift) {
            $shiftS = $shift['start'];
            $shiftE = $shift['end'];

            if ($shiftS <= $endTime && $endTime <= $shiftE) {
                $spentTime += $endTime - $shiftS + $spentShift;
            }

            if ($shiftS <= $startTime && $startTime <= $shiftE) {
                $spentTime -=  ($startTime - $shiftS);
            }
            $spentShift += $shift['spent'];
        }
        return $spentTime;
    }

    function _getSpentTimeDiffDate($listShift, $startTime, $endTime)
    {
        $spentShift = 0;
        $spentTime = 0;
        foreach ($listShift as $shift) {
            $shiftS = $shift['start'];
            $shiftE = $shift['end'];
            if ($shiftS <= $startTime && $startTime <= $shiftE) {
                $spentTime += 8 - ($startTime - $shiftS) - $spentShift;
            }
            if ($shiftS <= $endTime && $endTime <= $shiftE) {
                $spentTime += $endTime - $shiftS + $spentShift;
            }
            $spentShift += $shift['spent'];
        }

        return $spentTime;
    }

    /**
     * @param $startDate
     * @param $endDate
     * @return float
     */
    function getSpentTime($startDate, $endDate)
    {
        $dateTimeStart = Carbon::parse($startDate);
        $dateTimeEnd = Carbon::parse($endDate);
        $diffDate = $dateTimeEnd->diffInDays($dateTimeStart); //day unit
        $startTime = $dateTimeStart->hour + $dateTimeStart->minute / 60; //hour unit
        $endTime = $dateTimeEnd->hour + $dateTimeEnd->minute / 60;//hour unit
        $spentTime = max($diffDate - 1, 0) * 8.0;//hour unit
        $listShift = $this->getTimeShifts();
        if ($diffDate == 0) { //same date
            $spentTime += $this->_getSpentTimeSameDate($listShift, $startTime, $endTime);
        } else {//diff date
            $spentTime += $this->_getSpentTimeDiffDate($listShift, $startTime, $endTime);
        }
        return $spentTime;
    }

    //count spent in vacation days
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
            if($startDate == $endDate){
                if($endTime <= $startShift){
                    break;
                }elseif($endTime > $startShift && $endTime < $endShift){
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
            if($startTime <= $startShift){
                $spent->time += ($endShift - $startShift)/60;
                $spent->fullShift++;
            }elseif($startTime > $startShift && $startTime < $endShift){
                $spent->time += ($endShift - $startTime)/60;
                $spent->nonFullShift++;
            }
            if($startDate == $endDate){
                continue;
            }if($endTime > $startShift && $endTime < $endShift){
                $spent->time += ($endTime - $startShift)/60;
                $spent->nonFullShift++;
            }elseif($endTime >= $endShift){
                $spent->time += ($endShift - $startShift)/60;
                $spent->fullShift++;
            }
    
        }
        $spent->time += $middleDays*$workTimesPerDay;
        $spent->fullShift += $middleDays*$amountShiftsPerDay;
        return $spent;
    }
    //end-count spent in vacation days

    //not necessary for current template//explode vacation days to array object
    function vacationExplode($vacationDays){
        $arrVacation = [];
        $arrStart = explode(' ', $vacationDays->start);
        $arrEnd = explode(' ', $vacationDays->end);
        $startDate = $arrStart[0];
        $endDate = $arrEnd[0];
        $rawShift = DB::table('users')->find(Auth::user()->id)->shift;
        $rawArrShift = explode('-', $rawShift);
        $arrShift = [];
        foreach($rawArrShift as $ras){
            $sp = DB::table('session')->find($ras);
            array_push($arrShift, $ras, $sp->start, $sp->end);
        }
        for($thisDate = strtotime($startDate); $thisDate <= strtotime($arrEnd[0]); $thisDate += 24*60*60){
            if( $thisDate == strtotime($startDate) ){
                $startTime = $arrStart[1];
                for($y = 0; $y < count($arrShift); $y += 3){
                    if($startDate == $endDate){
                        $endTime = $arrEnd[1];
                        if(strtotime($endTime) <= strtotime($arrShift[$y+1])){
                            break;
                        }elseif( strtotime($endTime) > strtotime($arrShift[$y+1]) && strtotime($endTime) <= strtotime($arrShift[$y+2]) ){
                            if(strtotime($startTime) <= strtotime($arrShift[$y+1])){
                                $obj = (object)[
                                    'start' => date('Y-m-d', $thisDate) . ' ' .$arrShift[$y+1],
                                    'end' => date('Y-m-d', $thisDate) . ' ' .$endTime,
                                ];
                                $obj->spent = (strtotime($obj->end) - strtotime($obj->start))/60;
                                $obj->type = Constants::OFF_VACATION;
                                array_push($arrVacation, $obj);
                            }elseif(strtotime($startTime) > strtotime($arrShift[$y+1])){
                                $obj = (object)[
                                    'start' => date('Y-m-d', $thisDate) . ' ' .$startTime,
                                    'end' => date('Y-m-d', $thisDate) . ' ' .$endTime,
                                ];
                                $obj->spent = (strtotime($obj->end) - strtotime($obj->start))/60;
                                $obj->type = Constants::OFF_VACATION;
                                array_push($arrVacation, $obj);
                            }
                            break;
                        }
                    }
                    if( strtotime($startTime) <= strtotime($arrShift[$y+1]) ){
                        $obj = (object)[
                            'start' => date('Y-m-d', $thisDate) . ' ' .$arrShift[$y+1],
                            'end' => date('Y-m-d', $thisDate) . ' ' .$arrShift[$y+2],
                        ];
                        $obj->spent = (strtotime($obj->end) - strtotime($obj->start))/60;
                        $obj->type = Constants::OFF_VACATION;
                        array_push($arrVacation, $obj);
                    }elseif( strtotime($startTime) > strtotime($arrShift[$y+1]) && strtotime($startTime) < strtotime($arrShift[$y+2]) ){
                        $obj = (object)[
                            'start' => date('Y-m-d', $thisDate) . ' ' .$startTime,
                            'end' => date('Y-m-d', $thisDate) . ' ' .$arrShift[$y+2],
                        ];
                        $obj->spent = (strtotime($obj->end) - strtotime($obj->start))/60;
                        $obj->type = Constants::OFF_VACATION;
                        array_push($arrVacation, $obj);
                    }elseif( strtotime($startTime) >= strtotime($arrShift[$y+2]) ){
                        continue;
                    }
                }
            }elseif($thisDate < strtotime($endDate)){
                for($y = 0; $y < count($arrShift); $y += 3){
                    $obj = (object)[
                        'start' => date('Y-m-d', $thisDate) . ' ' .$arrShift[$y+1],
                        'end' => date('Y-m-d', $thisDate) . ' ' .$arrShift[$y+2],
                    ];
                    $obj->spent = (strtotime($obj->end) - strtotime($obj->start))/60;
                    $obj->type = Constants::OFF_VACATION;
                    array_push($arrVacation, $obj);
                }
            }elseif($thisDate == strtotime($endDate)){
                $endTime = $arrEnd[1];
                for($y = 0; $y < count($arrShift); $y += 3){
                    if( strtotime($endTime) <= strtotime($arrShift[$y+1]) ){
                        break;
                    }elseif( strtotime($endTime) > strtotime($arrShift[$y+1]) && strtotime($endTime) <= strtotime($arrShift[$y+2]) ){
                        $obj = (object)[
                            'start' => date('Y-m-d', $thisDate) . ' ' .$arrShift[$y+1],
                            'end' => date('Y-m-d', $thisDate) . ' ' .$endTime,
                        ];
                        $obj->spent = (strtotime($obj->end) - strtotime($obj->start))/60;
                        $obj->type = Constants::OFF_VACATION;
                        array_push($arrVacation, $obj);
                        break;
                    }elseif( strtotime($endTime) > strtotime($arrShift[$y+2]) ){
                        $obj = (object)[
                            'start' => date('Y-m-d', $thisDate) . ' ' .$arrShift[$y+1],
                            'end' => date('Y-m-d', $thisDate) . ' ' .$arrShift[$y+2],
                        ];
                        $obj->spent = (strtotime($obj->end) - strtotime($obj->start))/60;
                        $obj->type = Constants::OFF_VACATION;
                        array_push($arrVacation, $obj);
                    }
                }
            }
        }
        return $arrVacation;
    }
    //end-explode vacation days to array object

}
