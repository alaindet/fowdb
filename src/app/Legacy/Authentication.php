<?php

namespace App\Legacy;

use App\Services\Session;
use App\Exceptions\AuthenticationException;

class Authentication
{
    const NAME = 'admin-hash';

    public static function logout(): void
    {
        Session::delete(self::NAME);
    }

    public static function login(string $username, string $password): void
    {
        // Read the admin info from the database
        $admin = database()->get(
            "SELECT * FROM admins WHERE name = :name LIMIT 1",
            [':name' => $username],
            $first = true
        );

        // ERROR: Invalid username or password
        if (
            empty($admin) ||
            !password_verify($password, $admin['password'])
        ) {
            throw new AuthenticationException(
                "You provided invalid username and/or password"
            );
        }

        // Set the admin hash into the session
        Session::set(self::NAME, $admin['hash']);
    }
}
