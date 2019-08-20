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


    const COUNTRIES = [
        'vn' => 'Vietnam',
        'jp' => 'Jappan',
    ];
}

?>