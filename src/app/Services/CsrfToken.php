<?php

namespace App\Services;

use App\Base\Base;
use App\Services\Session;

class CsrfToken extends Base
{
    public const NAME = "_token";

    static public function createOrRefresh(): string
    {
        if (self::exists()) {
            return self::get();
        } else {
            return self::create();
        }
    }

    /**
     * Checks if token currently exists into the session
     *
     * @return boolean
     */
    static public function exists(): bool
    {
        return Session::exists(self::NAME);
    }

    /**
     * Creates a new anti-CSRF token
     *
     * @return string
     */
    static public function create(): string
    {
        $tokenValue = sha1(uniqid(mt_rand(), true));
        return Session::set(self::NAME, $tokenValue);
    }

    /**
     * Reads and returns the current anti-CSRF token from the session
     *
     * @return string
     */
    static public function get(): string
    {
        return Session::get(self::NAME);
    }

    /**
     * Checks a user-provided token with a session token, to validate it
     *
     * @param string $token
     * @return boolean
     */
    static public function check(string $token): bool
    {
        return Session::get(self::NAME) === $token;
    }

    /**
     * Returns the HTML <input> for the anti-CSRF token functionality
     *
     * @return string HTML string
     */
    static public function formInput(): string
    {
        $name = self::NAME;
        $value = self::get();
        return "<input type=\"hidden\" name=\"{$name}\" value=\"{$value}\">";
    }
}
