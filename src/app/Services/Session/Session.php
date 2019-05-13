<?php

namespace App\Services;

use App\Services\Session\Exceptions\SessionException;
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
        return $_SESSION[$name] ?? null;
    }

    /**
     * Removes an element from the session and returns it
     *
     * @param string $name
     * @return void
     */
    public static function pop(string $name)
    {
        if (!self::exists($name)) return null;

        $data = $_SESSION[$name];
        unset($_SESSION[$name]);
        return $data;
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
     * Alias for self::exists
     *
     * @param string $name
     * @return boolean
     */
    public static function has(string $name): bool
    {
        return self::exists($name);
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
        // Array already exists
        if (self::exists($name)) {

            // Add to array
            $previous = $_SESSION[$name];
            $previous[] = $value;
            $_SESSION[$name] = $previous;

        }
        
        // Array element doesn't exist, create it
        else {

            self::set($name, [$value]);
            
        }
    }
}
