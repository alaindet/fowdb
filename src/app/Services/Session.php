<?php

namespace App\Services;

use App\Exceptions\SessionException;
use App\Base\Base as BaseClass;

class Session extends BaseClass
{
    /**
     * Starts the session
     *
     * @return bool
     */
    public static function start(): bool
    {
        if (! session_start()) {
            throw new SessionException('Couldn\'t start the session');
        }

        return true;
    }

    /**
     * Reads and returns an element from the session
     *
     * @param string $name
     * @return mixed String | Array
     */
    public static function get(string $name)
    {
        if (! isset($_SESSION[$name])) {
            throw new SessionException(
                "Element \"{$name}\" doesn't exist on the session."
            );
        }

        return $_SESSION[$name];
    }

    /**
     * Stores a value into the session, then returns the value
     *
     * @param string $name
     * @param mixed $value
     * @return mixed Same as $value
     */
    public static function set(string $name, $value = null)
    {
        return $_SESSION[$name] = $value;
    }

    /**
     * Deletes a value from the session
     *
     * @param string $name
     * @return void
     */
    public static function delete(string $name): void
    {
        unset($_SESSION[$name]);
    }

    /**
     * Checks if an element with key === $name exists
     *
     * @param string $name
     * @return bool
     */
    public static function exists(string $name): bool
    {
        return isset($_SESSION[$name]);
    }

    /**
     * Adds a value to an existing session element or creates it
     * Existing element MUST BE an array
     *
     * @param string $name
     * @param mixed $value
     * @return mixed Same as $value
     */
    public static function add(string $name, $value = null)
    {
        // Element doesn't exist, create it
        if (!isset($_SERVER[$name])) return self::set($name, [$value]);

        // ERROR: Existing element is not an array
        if (!is_array($_SESSION[$name])) {
            throw new SessionException(
                "You cannot add a value to a non-array element"
            );
        }

        // Add to array
        if (is_array($_SESSION[$name])) return $_SESSION[$name][] = $value;
    }
}
