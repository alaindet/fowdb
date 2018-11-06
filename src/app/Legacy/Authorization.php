<?php

namespace App\Legacy;

use App\Services\Session;
use App\Exceptions\AuthorizationException;

class Authorization
{
    public const NAME = 'admin-hash';

    public static $bypassLevel = 1; // Super admin

    public static $levels = [
        'public' => 0,
        'admin' => 1,
        'user' => 2,
        'judge' => 3
    ];

    public static function level(): int
    {
        // ERROR: No admin hash stored into session
        if (!Session::exists(self::NAME)) return self::$levels['public'];

        $user = database_old()->get(
            "SELECT roles_id FROM users WHERE remember_token = :hash LIMIT 1",
            [':hash' => Session::get(self::NAME)],
            $first = true
        );

        // ERROR: Invalid hash stored into session
        if (empty($user)) return self::$levels['public'];

        // Authorization level > 0
        return (int) $user['roles_id'];
    }

    /**
     * Checks if current user's level matches given role's level
     *
     * @param string $role Role to check
     * @return boolean TRUE if logged user is authorized for given role
     */
    public static function check(string $role): bool
    {
        // ERROR: Invalid role
        if (!isset(self::$levels[$role])) {
            throw new AuthorizationException(
                "Role <strong>{$role}</strong> does not exist on FoWDB."
            );
        }

        // Check if logged user's level matches either required level
        // or bypass level
        return in_array(
            self::level(),
            [ self::$levels[$role], self::$bypassLevel ]
        );
    }

    /**
     * Checks if a user level meets the passed levels
     * Bounces back if not
     * 
     * @param $allowedLevels List of allowed levels
     * @return void
     */
    public static function allow(array $allowedLevels = []): void
    {
        $levels = array_merge($allowedLevels, [self::$bypassLevel]);

        if (!in_array(self::level(), $levels)) {
            throw new AuthorizationException(
                'You are not allowed to perform this action'
            );
        }
    }
}
