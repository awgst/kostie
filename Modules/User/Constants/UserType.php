<?php

namespace Modules\User\Constants;

class UserType
{
    const OWNER = 1;
    const REGULAR = 2;
    const PREMIUM = 3;

    /**
     * Function to get credit of user by type
     * @param int $type | optional
     * @return mix
     */
    public function credits(int $type=null)
    {
        $credits = [
            self::OWNER => 0,
            self::REGULAR => 20,
            self::PREMIUM => 40
        ];

        if (is_null($type)) {
            return $credits;
        }

        return $credits[$type];
    }
}