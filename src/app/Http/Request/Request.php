<?php

namespace App\Http\Request;

use App\Base\GetterSetterTrait;
use App\Http\Request\Input;

class Request
{
    use GetterSetterTrait;

    private $baseUrl;
    private $method;
    private $host;
    private $scheme;
    private $httpPort;
    private $httpsPort;
    private $path;
    private $queryString;

    private $app;

    public function baseUrl(string $value = null)
    {
        return $this->getterSetter('baseUrl', $value);
    }

    public function method(string $value = null)
    {
        return $this->getterSetter('method', $value);
    }

    public function host(string $value = null)
    {
        return $this->getterSetter('host', $value);
    }

    public function scheme(string $value = null)
    {
        return $this->getterSetter('scheme', $value);
    }

    public function httpPort(int $value = null)
    {
        return $this->getterSetter('httpPort', $value);
    }

    public function httpsPort(int $value = null)
    {
        return $this->getterSetter('httpsPort', $value);
    }

    public function queryString(string $value = null)
    {
        return $this->getterSetter('queryString', $value);
    }

    public function path(string $value = null)
    {
        if (isset($value)) {
            $pos = strpos($value, '?');
            if ($pos !== false) $value = substr($value, 0, $pos);
            $this->path = $value;
            return $this;
        }

        return $this->path;
    }

    public function app(string $name, $value = null)
    {
        if (isset($value)) {
            $this->app[$name] = $value;
            return $this;
        }

        return $this->app[$name];
    }

    public function input(): Input
    {
        return Input::getInstance();
    }
}
