<?php

namespace App\Legacy;

use App\Services\Session;

class Authorization
{
    const NAME = 'admin-hash';

    public static $levels = [
        'public' => 0,
        'admin' => 1,
        'judge' => 2
    ];

    public static function level(): int
    {
        // ERROR: No admin hash stored into session
        if (!Session::exists(self::NAME)) return 0;

        // Get admin info from database (INT)
        $admin = database()->get(
            "SELECT user_groups_id FROM admins WHERE hash = :hash",
            [':hash' => Session::get(self::NAME)],
            $first = true
        );

        // ERROR: Invalid hash stored into session
        if (empty($admin)) return 0;

        // Authorization level 1+
        return (int) $admin['user_groups_id'];
    }

    /**
     * Checks authorization based on desired role and current auth level
     *
     * @param string $role Role to check
     * @param int Authorization level of the current user
     * @return boolean
     */
    public static function check(string $role, $level): bool
    {
        if (!isset(self::$levels[$role])) return false;
        if (self::$levels[$role] !== $level) return false;
        return true;
    }
}
