<?php

namespace App\Http\Request;

use App\Base\Base;
use App\Base\Singleton;

class Input extends Base
{
    use Singleton;

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

    private function read(string $type, string $name = null, $escape = false)
    {
        $global = $this->globalArrayReference($type);
        if (!isset($name)) return $global;
        if (isset($global[$name])) {
            if ($escape) {
                return htmlentities($global[$name], ENT_QUOTES, 'UTF-8');
            } else {
                return $global[$name];
            }
        }
        return null;
    }

    private function &globalArrayReference(string $name): array
    {
        if ($name === 'GET') $ref =& $_GET;
        if ($name === 'POST') $ref =& $_POST;
        if ($name === 'FILES') $ref =& $_FILES;
        return $ref;
    }

    public function exists(string $name, string $type = 'GET'): bool
    {
        if ($type === 'GET') return isset($_GET[$name]);
        if ($type === 'POST') return isset($_POST[$name]);
        return false;
    }
}
