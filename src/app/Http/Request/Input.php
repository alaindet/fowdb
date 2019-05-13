<?php

namespace App\Http\Request;

use App\Base\Base;
use App\Base\Singleton;
use App\Services\Session\Session;

class Input extends Base
{
    use Singleton;

    public const PREVIOUS_INPUT = 'previous-input';

    public function previous()
    {
        return Session::pop(self::PREVIOUS_INPUT) ?? null;
    }

    public function get(string $name = null, $escape = false)
    {
        return $this->read('GET', $name, $escape);
    }

    public function post(string $name = null, $escape = false)
    {
        return $this->read('POST', $name, $escape);
    }

    public function files(string $name = null)
    {
        return $this->read('FILES', $name);
    }

    public function request(string $name = null)
    {
        return $this->read('REQUEST', $name);
    }

    public function getMultiple(array $names = null, $escape = false)
    {
        return $this->readMultiple('GET', $names, $escape);    
    }

    public function postMultiple(array $names = null, $escape = false)
    {
        return $this->readMultiple('POST', $names, $escape);    
    }

    /**
     * Reads a variable from the input
     *
     * @param string $type
     * @param string $name
     * @param boolean $escape
     * @return void
     */
    private function read(string $type, string $name = null, $escape = false)
    {
        // Alias the super global, like $_GET, $_POST or $_FILES
        $global = $this->globalArrayReference($type);

        // No name, return entire super global
        if (!isset($name)) return $global;

        // No value for this name
        if (!isset($global[$name]) || $global[$name] === '') return null;

        // Escape the value
        if ($escape) {
            return htmlspecialchars($global[$name], ENT_QUOTES, 'UTF-8');
        }

        // Return value as it is
        return $global[$name];
    }

    private function readMultiple(
        string $type,
        array $names = null,
        $escape = false
    )
    {
        // Alias the super global, like $_GET, $_POST or $_FILES
        $global = $this->globalArrayReference($type);

        // No name, return entire super global
        if (!isset($names)) return $global;

        // Initialize results array
        $results = [];

        foreach ($names as $name) {

            // No value for this name
            if (!isset($global[$name]) || $global[$name] === '') {
                continue;
            }

            // Escape the value
            if ($escape) {
                $value = htmlspecialchars($global[$name], ENT_QUOTES, 'UTF-8');
                $results[$name] = $value;
            }
            
            // Read from global without escaping (default)
            else {
                $results[$name] = $global[$name];
            }

        }

        return $results;
    }

    /**
     * Returns a reference to global arrays by its name
     *
     * @param string $name
     * @return array
     */
    private function &globalArrayReference(string $name): array
    {
        if ($name === 'GET' || $name === 'get') $ref =& $_GET;
        elseif ($name === 'POST' || $name === 'post') $ref =& $_POST;
        elseif ($name === 'REQUEST' || $name === 'request') $ref =& $_REQUEST;
        elseif ($name === 'FILES' || $name === 'files') $ref =& $_FILES;

        return $ref;
    }

    public function &getGlobalReference(string $name): array
    {
        return $this->globalArrayReference($name);
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
            $array = $this->globalArrayReference($name);
            return isset($array[$name]);
        }

        if (isset($_GET[$name])) return true;
        if (isset($_POST[$name])) return true;
        if (isset($_FILES[$name])) return true;

        return false;
    }

    /**
     * Alias for Input::exists()
     *
     * @param string $name
     * @param string $type (optional) Name of the global array to check
     * @return boolean
     */
    public function has(string $name, string $type = null): bool
    {
        return $this->exists($name);
    }

    /**
     * Return all input data
     *
     * @return array
     */
    public function all(): array
    {
        return [
            'GET' => $_GET,
            'POST' => $_POST,
            'FILES' => $_FILES
        ];
    }
}
