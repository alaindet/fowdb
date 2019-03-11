<?php

namespace App\Http\Request;

use App\Http\Request\Input;
use App\Services\Alert;
use App\Services\Validation\Validation;
use App\Services\Configuration\Configuration;
use App\Utils\Arrays;

class Request
{
    private $baseUrl;
    private $method;
    private $host;
    private $scheme;
    private $httpPort;
    private $httpsPort;
    private $path;
    private $queryString;
    private $app;
    private $validationException = ValidationException::class;

    public function setBaseUrl(string $baseUrl = null): Request
    {
        if (isset($baseUrl)) $this->baseUrl = $baseUrl;
        return $this;
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function setMethod(string $method = null): Request
    {
        if (isset($method)) $this->method = $method;
        return $this;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setHost(string $host = null): Request
    {
        if (isset($host)) $this->host = $host;
        return $this;
    }

    public function getHost(): string
    {
        return $this->host;
    }
    
    public function setScheme(string $scheme = null): Request
    {
        if (isset($scheme)) $this->scheme = $scheme;
        return $this;
    }

    public function getScheme(): string
    {
        return $this->scheme;
    }

    public function setHttpPort(int $httpPort = null): Request
    {
        if (isset($httpPort)) $this->httpPort = $httpPort;
        return $this;
    }

    public function getHttpPort(): int
    {
        return $this->httpPort;
    }

    public function setHttpsPort(int $httpsPort = null): Request
    {
        if (isset($httpsPort)) $this->httpsPort = $httpsPort;
        return $this;
    }

    public function getHttpsPort(): int
    {
        return $this->httpsPort;
    }

    public function setQueryString(string $queryString = null): Request
    {
        if (isset($queryString)) $this->queryString = $queryString;
        return $this;
    }

    public function getQueryString(): string
    {
        return $this->queryString;
    }

    public function setPath(string $path = null): Request
    {
        if (isset($path)) {
            $pos = strpos($path, '?');
            if ($pos !== false) $path = substr($path, 0, $pos);
            $this->path = $path;
        }

        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Sets or reads app data
     *
     * @param string $name
     * @param any $value
     * @return void
     */
    public function app(string $name, $value = null)
    {
        if (isset($value)) {
            $this->app[$name] = $value;
            return $this;
        }

        return $this->app[$name] ?? null;
    }

    public function input(): Input
    {
        return Input::getInstance();
    }

    public function getCurrentUrl(bool $withQueryString = false): string
    {
        (empty($this->queryString))
            ? $queryString = ""
            : $queryString = "?{$this->queryString}";

        return url($this->path) . $queryString;
    }

    /**
     * Activates JSON validation errors (used in API)
     *
     * @return Request
     */
    public function api(): Request
    {
        Configuration::getInstance()->set('api', true);

        return $this;
    }

    /**
     * Validate inputs, throws validation exception on fail
     *
     * @param string $httpMethod
     * @param array $toValidate
     * @param array $input
     * @return void
     */
    public function validate(array $rules): void
    {
        $method = $this->method;
        $data = $this->input()->$method();
        $validation = new Validation;
        $validation->setData($data);
        $validation->setRules($rules);
        $validation->validate();
    }
}
