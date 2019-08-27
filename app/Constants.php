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
    const PENDING_OT = 0;
    const REJECT_OT = -1;
    const DRAFT_OT = -2;

    const COUNTRIES = [
        'vn' => 'Vietnam',
        'jp' => 'Jappan',
    ];

    const APPROVED_VACATION = 1;
    const REJECTED_VACATION = -1;
    const PENDING_VACATION = 0;

    const EARLY_VACATION = -1;
    const LATE_VACATION = -2;
    const OUT_VACATION = -3;
    const OTHER_VACATION = -4;

    const VACATION_TYPE = [
        Constants::EARLY_VACATION => 'Leave Early',
        Constants::LATE_VACATION => 'Come Late',
        Constants::OUT_VACATION => 'Go Out',
        Constants::OTHER_VACATION => 'Other',
    ];

    const AUTHORIZE_AUTH = 'auth';
    const AUTHORIZE_MANAGER = 'auth:' . self::ROLE_ADMIN . ',' . self::ROLE_STAFF;
    const AUTHORIZE_MEMBER = 'auth:' . self::ROLE_MEMBER;
    const AUTHORIZE_ADMIN = 'auth:' . self::ROLE_ADMIN;

    const AUTHORIZE_AJAX_REQUEST = 'AllowOnlyAjaxRequests';
}

