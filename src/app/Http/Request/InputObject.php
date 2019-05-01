<?php

namespace App\Http\Request;

use App\Base\Base;
use App\Base\Singleton;
use App\Services\Session;

class InputObject extends Base
{
    use Singleton;

    private const PREVIOUS_INPUT = 'previous-input';
    private $escape = false;

    /**
     * Set global escaping for input
     *
     * @param bool $escape
     * @return self
     */
    public function escape(bool $escape = true): self
    {
        $this->escape = true;
        return $this;
    }

    /**
     * Reads the previous input (after validation errors)
     * 
     * @return object
     */
    public function previous()
    {
        return Session::pop(self::PREVIOUS_INPUT) ?? null;
    }

    /**
     * Accesses GET parameters
     * InputObject::post and InputObject::files are equivalent to this method
     * 
     * If no $names is passed, all GET parameters are returned
     * If $names is a string, a single GET parameter is returned
     * If $names is array, GET parameters are extracted (use as whitelist)
     *
     * @param string|string[] $names Read above
     * @return object All GET parameters or a single GET parameter value
     */
    public function get($names = null)
    {
        return $this->read('GET', $names);
    }

    public function post($names = null)
    {
        return $this->read('POST', $names);
    }

    public function files($names = null)
    {
        return $this->read('FILES', $names);
    }

    /**
     * Reads a variable from the input
     *
     * @param string $type
     * @param string|string[] $names
     * @param boolean $escape
     * @return any
     */
    private function read(string $type, $names = null)
    {
        // Alias the super global, like $_GET, $_POST or $_FILES
        $global = $this->getGlobalReference($type);

        // No name, return entire super global
        if ($names === null) {
            return (object) $global;
        }

        // Define accessor function (can escape the value)
        if ($this->escape) {
            $accessor = function ($value) {
                return htmlspecialchars($value, ENT_QUOTES, "UTF-8");
            };
        } else {
            $accessor = function ($value) {
                return $value;
            };
        }

        // Read multiple values from global array
        if (is_array($names)) {
            $results = [];
            foreach ($names as $name) {
                $results[$name] = $accessor($global[$name]);
            }
            return $results;
        }

        // No value for this name
        if (!isset($global[$name]) || $global[$name] === '') {
            return null;
        }

        // Single value, read it from global array
        return $accessor($global[$name]);
    }

    /**
     * Checks if a variable exists on the input
     *
     * @param string $name
     * @param string $type (optional) Name of the global array to check
     * @return boolean
     */
    public function exists(string $name, string $type = null): bool
    {
        if (isset($type)) {
            $global = $this->getGlobalReference($name);
            return isset($global[$name]);
        }

        if (isset($_GET[$name])) return true;
        if (isset($_POST[$name])) return true;
        if (isset($_FILES[$name])) return true;

        return false;
    }

    /**
     * Returns a reference to global arrays by its name
     *
     * @param string $name
     * @return array
     */
    private function &getGlobalReference(string $name): array
    {
        if ("GET" === $name) $ref = &$_GET;
        if ("POST" === $name) $ref = &$_POST;
        if ("FILES" === $name) $ref = &$_FILES;

        return $ref;
    }
}
