<?php

namespace App\Http\Controllers;

use App\Constants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QTController extends Controller
{
    public function GetQT()
    {
        $user = Auth::user();
        $setting = DB::table('settings')->where('company_id', $user->company_id)->first(['vacation_per_year', 'short_leave', 'hour_step']);
        $idShiftList = explode('.', DB::table('users')->find($user->id)->shift);
        $shifts = DB::table('shifts')->whereIn('id', $idShiftList)->orderBy('start', 'asc')->get();
        $dynamicReason = DB::table('reasons')->where('company_id', $user->company_id)->get(['reason', 'id']);
        $vacation = $setting->vacation_per_year;
        $vList = DB::table('vacations')
            ->where('is_approved', Constants::APPROVED_VACATION)
            ->where('user_id', Auth::id())
            ->get(['start', 'end']);
        $spent = 0;
        foreach ($vList as $v) {
            $spent += $this->VacationSpent((object)[
                'start' => $v->start,
                'end' => $v->end
            ]);
        }
        $time_remaining = $vacation - $spent;
        return view('qt.post', [
            'setting' => $setting,
            'shifts' => $shifts,
            'dynamicReason' => $dynamicReason,
            'time_remaining' => $time_remaining,
            'vacation' => $vacation,
        ]);
    }

    public function GetList()
    {
        $vacation = DB::table('settings')->where('company_id', Auth::user()->company_id)->select('vacation_per_year')->get()[0]->vacation_per_year;
        $vList = DB::table('vacations')->where([
            ['is_approved', '!=', Constants::REJECTED_VACATION],
            ['user_id', Auth::id()],
        ])->select('start', 'end', 'is_approved')->get();
        $aSpent = 0;
        $eSpent = 0;
        foreach ($vList as $v) {
            if ($v->is_approved == Constants::APPROVED_VACATION) {
                $aSpent += $this->VacationSpent((object)[
                    'start' => $v->start,
                    'end' => $v->end
                ]);
            }
            $eSpent += $this->VacationSpent((object)[
                'start' => $v->start,
                'end' => $v->end
            ]);
        }
        $aTimeRemaining = $vacation - $aSpent;
        $eTimeRemaining = $vacation - $eSpent;
        $history = DB::table('vacations')->where('user_id', Auth::id())->orderBy('updated_at', 'desc')->select('start', 'end', 'is_approved')->get();
        return view('qt.list', [
            'aTimeRemaining' => $aTimeRemaining,
            'eTimeRemaining' => $eTimeRemaining,
            'vacation' => $vacation,
            'history' => $history,
            'today' => date('Y-m-d'),
        ]);
    }

    public function PostQT(Request $request)
    {
        $userId = Auth::id();
        $sDate = $request->startDate;
        $eDate = $request->endDate;
        $sTime = $request->startTime . ':00';
        $eTime = $request->endTime . ':00';
        $type = $request->type;
        $comment = $request->comment;
        $start = $sDate . ' ' . $sTime;
        $end = $eDate . ' ' . $eTime;
        $now = date("Y-m-d H:i:s");
        DB::table('vacations')->insert([
            'user_id' => $userId,
            'start' => $start,
            'end' => $end,
            'comment' => $comment,
            'is_approved' => Constants::PENDING_VACATION,
            'type' => $type,
            'created_at' => $now,
            'updated_at' => $now,
            'created_by' => $userId,
        ]);
        return redirect('qt/list');
    }

    /**
     * count spent(hours) in vacation days
     * @param object
     * @return number
     */
    function VacationSpent($vacationDays)
    {
        $spent = 0;
        $arrStart = explode(' ', $vacationDays->start);
        $arrEnd = explode(' ', $vacationDays->end);
        $startDate = strtotime($arrStart[0]);
        $startTime = strtotime($arrStart[1]);
        $endDate = strtotime($arrEnd[0]);
        $endTime = strtotime($arrEnd[1]);
        $middleDays = max(0, ($endDate - $startDate) / 3600 / 24 - 1);
        $shifts = $this->getTimeShifts();
        $workTimesPerDay = 0;
        foreach ($shifts as $shift) {
            $startShift = strtotime($shift['start']);
            $endShift = strtotime($shift['end']);
            $workTimesPerDay += $shift['spent'];
            if ($startDate == $endDate) {
                if ($endTime <= $startShift) {
                    break;
                } elseif ($endTime > $startShift && $endTime <= $endShift) {
                    if ($startTime <= $startShift) {
                        $spent += ($endTime - $startShift) / 3600;
                    } elseif ($startTime > $startShift) {
                        $spent += ($endTime - $startTime) / 3600;
                    }
                    break;
                }
            }
            if ($startTime <= $startShift) {
                $spent += $shift['spent'];
            } elseif ($startTime > $startShift && $startTime < $endShift) {
                $spent += ($endShift - $startTime) / 3600;
            }
            if ($startDate == $endDate || $endTime <= $startShift) {
                continue;
            } elseif ($endTime > $startShift && $endTime < $endShift) {
                $spent += ($endTime - $startShift) / 3600;
            } elseif ($endTime >= $endShift) {
                $spent += $shift['spent'];
            }
        }
        $spent += $middleDays * $workTimesPerDay;
        return $spent;
    }

    /**
     * create list shift inlude start, end, spent(hour) for current user
     * @return array
     */
    function getTimeShifts()
    {
        $idShiftList = explode('.', Auth::user()->shift);
        $shifts = DB::table('shifts')->whereIn('id', $idShiftList)->get()->all();
        return array_map(function ($shift) {
            return [
                'start' => $shift->start,
                'end' => $shift->end,
                'spent' => (strtotime($shift->end) - strtotime($shift->start)) / 60 / 60,
            ];
        }, $shifts);
    }

}
