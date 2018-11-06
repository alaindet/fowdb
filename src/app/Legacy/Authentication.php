<?php

namespace App\Legacy;

use App\Services\Session;
use App\Exceptions\AuthenticationException;

class Authentication
{
    const NAME = 'admin-hash';

    public static function logout(): void
    {
        $hash = Session::pop(self::NAME);

        database_old()->update(
            'users',
            ['remember_token' => ''],
            'remember_token = :hash',
            [':hash' => $hash]
        );
    }

    public static function login(string $username, string $password): void
    {
        // Read the admin info from the database
        $user = database_old()->get(
            "SELECT * FROM users WHERE username = :name LIMIT 1",
            [':name' => $username],
            $first = true
        );

        // ERROR: Invalid username or password
        if (empty($user) || !password_verify($password, $user['password'])) {
            throw new AuthenticationException(
                "You provided invalid username and/or password"
            );
        }

        // Random hash as a remember token
        $hash = password_hash(
            time().sha1(uniqid(mt_rand(), true)),
            PASSWORD_BCRYPT
        );

        // Store the hash into the database
        database_old()->update(
            'users',
            ['remember_token' => $hash],
            'username = :name',
            [':name' => $username]
        );

        // Store the hash into the session
        Session::set(self::NAME, $hash);
    }
}
