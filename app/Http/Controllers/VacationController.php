<?php

namespace App\Http\Controllers;

use App\Constants;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class VacationController extends Controller
{
    /**
     * @return View
     */
    public function getVacation()
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

    /**
     * @return View
     */
    public function getList()
    {
        $vacation = DB::table('settings')->where('company_id', Auth::user()->company_id)->first('vacation_per_year');
        $vacationPerYear = $vacation ? $vacation->vacation_per_year : 12;

        $vList = DB::table('vacations')
            ->where([
                ['is_approved', '!=', Constants::REJECTED_VACATION],
                ['user_id', Auth::id()],
            ])
            ->get(['start', 'end', 'is_approved']);

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
        $aTimeRemaining = $vacationPerYear - $aSpent;
        $eTimeRemaining = $vacationPerYear - $eSpent;
        $history = DB::table('vacations')
            ->where('user_id', Auth::id())
            ->orderBy('updated_at', 'desc')
            ->get(['start', 'end', 'is_approved']);

        return view('qt.list', [
            'aTimeRemaining' => $aTimeRemaining,
            'eTimeRemaining' => $eTimeRemaining,
            'vacation' => $vacationPerYear,
            'history' => $history,
            'today' => date('Y-m-d'),
        ]);
    }


    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function postVacation(Request $request)
    {
        $userId = Auth::id();
        $sDate = $request->input('startDate');
        $eDate = $request->input('endDate');
        $sTime = $request->input('startTime') . ':00';
        $eTime = $request->input('endTime') . ':00';
        $type = $request->input('type');
        $comment = $request->input('comment');

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

        return redirect('vacation/list');
    }

    /**
     * count spent(hours) in vacation days
     * @param object
     * @return number
     */
    function VacationSpent($vacationDays)
    {
        $arrStart = explode(' ', $vacationDays->start);
        $arrEnd = explode(' ', $vacationDays->end);
        $startDate = strtotime($arrStart[0]);
        $startTime = strtotime($arrStart[1]);
        $endDate = strtotime($arrEnd[0]);
        $endTime = strtotime($arrEnd[1]);
        $middleDays = max(0, ($endDate - $startDate) / 3600 / 24 - 1);
        $dayWorkTime = 0;
        $shifts = $this->getTimeShifts($dayWorkTime);
        $spent = $middleDays * $dayWorkTime;
        if ($startDate == $endDate) {
            $spent += $this->_getSpentTimeSameDate($shifts, $startTime, $endTime);
        } else {
            $spent += $this->_getSpentTimeDiffDate($shifts, $startTime, $endTime, $dayWorkTime * 3600);
        }

        return $spent;
    }

    function _getSpentTimeSameDate($shifts, $startTime, $endTime)
    {
        $spentTime = 0;
        $spentShift = 0;
        foreach ($shifts as $shift) {
            $startShift = strtotime($shift['start']);
            $endShift = strtotime($shift['end']);
            if ($startShift <= $endTime && $endTime <= $endShift) {
                $spentTime += $endTime - $startShift + $spentShift;
            }
            if ($startShift <= $startTime && $startTime <= $endShift) {
                $spentTime -= $startTime - $startShift + $spentShift;
            }
            $spentShift += $shift['spent'] * 3600;
        }
        return $spentTime / 3600;//hours unit
    }

    function _getSpentTimeDiffDate($shifts, $startTime, $endTime, $dayWorkTime)
    {
        $spentTime = 0;
        $spentShift = 0;
        foreach ($shifts as $shift) {
            $startShift = strtotime($shift['start']);
            $endShift = strtotime($shift['end']);
            if ($startShift <= $startTime && $startTime <= $endShift) {
                $spentTime += $dayWorkTime - ($startTime - $startShift) - $spentShift;
            }
            if ($startShift <= $endTime && $endTime <= $endShift) {
                $spentTime += $endTime - $startShift + $spentShift;
            }
            $spentShift += $shift['spent'] * 3600;
        }
        return $spentTime / 3600;//hours unit
    }


    /**
     * create list shift inlude start, end, spent(hour) for current user
     * @param $dayOfWork
     * @return array
     */
    function getTimeShifts(&$dayOfWork)
    {
        $idShiftList = explode('.', Auth::user()->shift);
        $shifts = DB::table('shifts')->whereIn('id', $idShiftList)->get()->all();

        return array_map(function ($shift) use (&$dayOfWork) {
            $spent = (strtotime($shift->end) - strtotime($shift->start)) / 3600;
            $dayOfWork += $spent;
            return [
                'start' => $shift->start,
                'end' => $shift->end,
                'spent' => $spent,
            ];
        }, $shifts);
    }

}
