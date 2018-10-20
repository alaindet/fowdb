<?php

namespace App\Http\Request;

use App\Base\Base;
use App\Base\Singleton;

class Input extends Base
{
    use Singleton;

    public function get(string $name = null, $default = null)
    {
        return $this->read('GET', $name, $default);
    }

    public function post(string $name = null, $default = null)
    {
        return $this->read('POST', $name, $default);
    }

    public function files(string $name = null, $default = null)
    {
        return $this->read('FILES', $name, $default);
    }

    private function read(string $type, string $name = null, $default = null)
    {
        $global = $this->globalArrayReference($type);
        if (!isset($name)) return $global;
        if (isset($global[$name])) return $global[$name];
        if (isset($default)) return $default;
        return null;
    }

    private function &globalArrayReference(string $name): array
    {
        if ($name === 'GET') $ref =& $_GET;
        if ($name === 'POST') $ref =& $_POST;
        if ($name === 'FILES') $ref =& $_FILES;
        return $ref;
    }
}
