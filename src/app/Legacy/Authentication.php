<?php

namespace App\Legacy;

use App\Services\Session;
use App\Exceptions\AuthenticationException;

class Authentication
{
    const NAME = 'admin-hash';

    public static function logout(): void
    {
        database()
            ->update(
                statement('update')
                    ->table('users')
                    ->values(['remember_token' => ':notoken'])
                    ->where('remember_token = :token')
            )
            ->bind([
                ':notoken' => '',
                ':token' => Session::pop(self::NAME)
            ])
            ->execute();
    }

    public static function login(string $username, string $password): void
    {
        // Read the admin info from the database
        $user = database()
            ->select(
                statement('select')
                    ->fields('password')
                    ->from('users')
                    ->where('username = :name')
                    ->limit(1)
            )
            ->bind([':name' => $username])
            ->first();

        // ERROR: Invalid username or password
        if (empty($user) || !password_verify($password, $user['password'])) {
            throw new AuthenticationException(
                "You provided invalid username and/or password"
            );
        }

        // Random hash as a remember token
        $kindOfUnique = time().sha1(uniqid(mt_rand(), true));
        $token = password_hash($kindOfUnique, PASSWORD_BCRYPT);

        // Store the hash into the database
        database()
            ->update(
                statement('update')
                    ->table('users')
                    ->values(['remember_token' => ':token'])
                    ->where('username = :name')
            )
            ->bind([
                ':token' => $token,
                ':name' => $username
            ])
            ->execute();

        // Store the hash into the session
        Session::set(self::NAME, $token);
    }
}
