<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Constants;

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
        $rawHistory = DB::table('vacations')->where([
            ['user_id', Auth::user()->id],
            ['is_approved', 1]
        ])->select('start', 'end', 'spent', 'type')->get();
        $history = $this->descSoftHistory(  $this->filterHistory($rawHistory, date('Y-m-d')) );
        $pendding = DB::table('vacations')->where([
            ['is_approved', Constants::PENDDING_VACATION],
            ['user_id', Auth::user()->id],
            ])->count();
        return view('qt.list',[
            'time_remaining'=>$time_remaining,
            'vacation'=>$vacation,
            'history'=>$history,
            'pendding'=>$pendding,
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
        if($request->LEDate){
            $date = $request->LEDate;
            $session = $request->session;
            $type = $request->type;
            $spent = explode(' ', $request->time)[0];
            $comment = $request->comment;
            $arr = explode('-', DB::table('setting')->select('workTime')->get()[0]->workTime);
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
            $comment = $request->comment;
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
        }elseif($request->startDT){
            $start =  $request->startDT.':00';
            $end =  $request->endDT.':00';
            $comment = $request->comment;
            $explodedVacation = $this->vacationExplode((object)[
                'start'=>$start,
                'end'=>$end,
            ]);
            $spent = 0;
            foreach($explodedVacation as $ev){
                $spent += $ev->spent;
            }
            DB::table('vacations')->insert([
                'user_id'=>$userId,
                'start'=>$start,
                'end'=>$end,
                'spent'=>$spent,
                'comment'=>$comment,
                'type'=>Constants::OFF_VACATION,
                'is_approved'=>Constants::PENDDING_VACATION,
                'created_at'=>date("Y-m-d H:i:s"),
                'created_by'=>$userId,
            ]);
        }
        return redirect('qt/list/pendding');
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

    //filter history by date
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

    //handling vacation days
    function vacationExplode($vacationDays){
        $arrVacation = [];
        $arrStart = explode(' ', $vacationDays->start);
        $arrEnd = explode(' ', $vacationDays->end);
        $startDate = $arrStart[0];
        $endDate = $arrEnd[0];
        $shift = DB::table('users')->find(Auth::user()->id)->shift;
        $arrShift = explode('-', $shift);
        for($thisDate = strtotime($startDate); $thisDate <= strtotime($arrEnd[0]); $thisDate += 24*60*60){
            if( $thisDate == strtotime($startDate) ){
                $startTime = $arrStart[1];
                for($y = 0; $y < count($arrShift); $y += 3){
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
    //end-handling vacation days

}
