<?php

namespace App;

class Constants
{
    const WEB_NAME = 'hrm';

    const ROLE_ADMIN = '1';
    const ROLE_STAFF = '2';
    const ROLE_MEMBER = '0';

    const IS_DELETED = 1;
    const NO_DELETED = 0;

    const APPROVED_OT = 1;
    const PENDDING_OT = 0;
    const REJECT_OT = -1;
    const DRAFT_OT = -2;

    const COUNTRIES = [
        'vn' => 'Vietnam',
        'jp' => 'Jappan',
    ];

    const APPROVED_VACATION = 1;
    const REJECTED_VACATION = -1;
    const PENDDING_VACATION = 0;

    const EARLY_VACATION = 1;
    const LATE_VACATION = 2;
    const OUT_VACATION = 3;
    const OFF_VACATION = 4;

    const VACATION_TYPE = [
        Constants::EARLY_VACATION => 'Leave Early',
        Constants::LATE_VACATION => 'Come Late',
        Constants::OUT_VACATION => 'Go Out',
        Constants::OFF_VACATION => 'Vacation',
    ];

    const VACATION_REASON = [
        'Sick'=>'I am sick',
        'Go home'=>'I must go home',
    ];
}

?>