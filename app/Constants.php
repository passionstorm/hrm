<?php

namespace App;

class Constants
{
    const ROLE_ADMIN = '1';
    const ROLE_STAFF = '2';
    const ROLE_MEMBER = '0';

    const IS_DELETED = 1;
    const NO_DELETED = 0;

    const APPROVED_OT = 1;
    const PENDDING_OT = 0;
    const REJECT_OT = -1;
    const DRAFT_OT = -2;

    const MORNING_SESSION = 'm';
    const AFTERNOON_SESSION = 'a';
    const EVENING_SESSION = 'e';

    const COUNTRIES = [
        'vn' => 'Vietnam',
        'jp' => 'Jappan',
    ];

    const APPROVED_VACATION = 1;
    const REJECTED_VACATION = -1;
    const PENDDING_VACATION = 0;
}

?>