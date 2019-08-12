<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use DB;
use Constants;

class QTController extends Controller
{
    public function GetQT(){
        $setting = DB::table('setting')->select('vacation', 'workTime')->get()[0];
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
            ['date', date('Y-m-d')],
            ['is_approved', 1]
        ])->orderBy('check_out', 'desc')->select('check_out', 'check_in', 'spent')->get();
        $pendding = DB::table('vacations')->where('is_approved', Constants::PENDDING_VACATION)->count();
        return view('qt.list',[
            'time_remaining'=>$time_remaining,
            'vacation'=>$vacation,
            'history'=>$history,
            'pendding'=>$pendding,
            'today'=>date('Y-m-d'),
        ]);
    }

    public function GetListPendding(){
        $pendding = DB::table('vacations')->where('is_approved', Constants::PENDDING_VACATION)->select('check_out', 'check_in', 'date', 'spent')->orderBy('date', 'asc')->get();
        return view('qt.listPendding',[
            'pendding'=>$pendding,
        ]);
    }

    public function shortLeave(Request $request){
        $isCheckedOut = DB::table('vacations')->where([
            ['user_id', Auth::user()->id],
            ['check_in', null],
          ])->count();
        $dateTime = date('Y-m-d H:i:s');
        if($isCheckedOut){
            $id = DB::table('vacations')->where([
                ['user_id', Auth::user()->id],
                ['check_in', null],
              ])->select('id')->get()[0]->id;
            $x = DB::table('vacations')->find($id);
            $checkIn = date("H:i:s");
            $spent = number_format((strtotime($checkIn) - strtotime($x->check_out))/60, 0);
            if($spent > DB::table('setting')->select('shortLeave')->get()[0]->shortLeave){
                DB::table('vacations')->where('id', $id)->update([
                      'spent'=>$spent,
                      'check_in'=>$checkIn,
                      'updated_at'=>$dateTime,
                      'updated_by'=>Auth::user()->id,
                      'is_approved'=>1,
                  ]);
                $time_remaining = DB::table('users')->find(Auth::user()->id)->time_remaining - $spent;
                DB::table('users')->where('id', Auth::user()->id)->update([
                    'time_remaining'=>$time_remaining
                ]);
                return response()->json([
                    'result'=>1,
                    'time_remaining'=>$time_remaining,
                    'date'=>$x->date,
                ]);
            }
            DB::table('vacations')->where('id', $id)->delete();
            return response()->json([
                'result'=>1,
                'spent'=>$spent,
            ]);
        }else{
            $checkOut = date("H:i:s");
            DB::table('vacations')->insert([
                'user_id'=>Auth::user()->id,
                'date'=>date('Y-m-d'),
                'check_out'=>$checkOut,
                'created_at'=>$dateTime,
                'created_by'=>Auth::user()->id,
            ]);
            return response()->json([
                'result'=>0,
            ]);
        }

    }

    public function PostQT(Request $request){
        $userId = Auth::user()->id;
        if($request->dayForSession){
            $dayForSession = $request->dayForSession;
            $session = $request->session;
            $comment = $request->comment;
            $arr = explode('-', DB::table('setting')->select('workTime')->get()[0]->workTime);
            for($i = 0; $i < count($arr); $i++){
                if($arr[$i] == $session){
                    $check_out = str_replace('h',':', $arr[$i+1]).':00';
                    $check_in = str_replace('h',':', $arr[$i+2]).':00';
                    break;
                }
            }
            $spent = number_format((strtotime($check_in) - strtotime($check_out))/60, 0);
            DB::table('vacations')->insert([
                'user_id'=>$userId,
                'date'=>$dayForSession,
                'check_out'=>$check_out,
                'check_in'=>$check_in,
                'spent'=>$spent,
                'comment'=>$comment,
                'is_approved'=>0,
                'created_at'=>date("Y-m-d H:i:s"),
                'created_by'=>$userId,
            ]);
        }elseif($request->allDay){
            $allDay = $request->allDay;
            $comment = $request->comment;
            $arr = explode('-', DB::table('setting')->select('workTime')->get()[0]->workTime);
            for($i = 0; $i < count($arr); $i++){
                if($arr[$i] == Constants::MORNING_SESSION || $arr[$i] == Constants::AFTERNOON_SESSION || $arr[$i] == Constants::EVENING_SESSION){
                    $check_out = str_replace('h',':', $arr[$i+1]).':00';
                    $check_in = str_replace('h',':', $arr[$i+2]).':00';
                    $spent = number_format((strtotime($check_in) - strtotime($check_out))/60, 0);
                    DB::table('vacations')->insert([
                        'user_id'=>$userId,
                        'date'=>$allDay,
                        'check_out'=>$check_out,
                        'check_in'=>$check_in,
                        'spent'=>$spent,
                        'comment'=>$comment,
                        'is_approved'=>0,
                        'created_at'=>date("Y-m-d H:i:s"),
                        'created_by'=>$userId,
                    ]);
                    $i+=2;
                }
            }
        }elseif($request->startDate){
            $startDate = strtotime($request->startDate);
            $endDate = strtotime($request->endDate);
            $comment = $request->comment;
            $arr = explode('-', DB::table('setting')->select('workTime')->get()[0]->workTime);
            for($d = $startDate; $d <= $endDate; $d+=24*60*60){
                for($i = 0; $i < count($arr); $i++){
                    if($arr[$i] == Constants::MORNING_SESSION || $arr[$i] == Constants::AFTERNOON_SESSION || $arr[$i] == Constants::EVENING_SESSION){
                        $check_out = str_replace('h',':', $arr[$i+1]).':00';
                        $check_in = str_replace('h',':', $arr[$i+2]).':00';
                        $spent = number_format((strtotime($check_in) - strtotime($check_out))/60, 0);
                        DB::table('vacations')->insert([
                                'user_id'=>$userId,
                                'date'=>Date('Y-m-d', $d),
                                'check_out'=>$check_out,
                                'check_in'=>$check_in,
                                'spent'=>$spent,
                                'comment'=>$comment,
                                'is_approved'=>0,
                                'created_at'=>date("Y-m-d H:i:s"),
                                'created_by'=>$userId,
                            ]);
                        $i+=2;
                    }
                }
            }
        }
        return redirect('qt/list/pendding');
    }

    public function SearchByDate(Request $request){
        $data = DB::table('vacations')->where([
            ['date', $request->date],
            ['is_approved', Constants::APPROVED_VACATION],
        ])->orderBy('check_out', 'desc')->select('check_out', 'check_in', 'spent')->get();
        return response()->json([
            'data'=>$data
        ]);
    }
}
