<?php

namespace App\Http\Request;

use App\Http\Request\Input\InputManager;
use App\Services\Validation\Validation;
use App\Utils\Uri;
use App\Http\Request\Input\InputObject;

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

    public function setBaseUrl(string $baseUrl): Request
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function setMethod(string $method): Request
    {
        $this->method = $method;
        return $this;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setHost(string $host): Request
    {
        $this->host = $host;
        return $this;
    }

    public function getHost(): string
    {
        return $this->host;
    }
    
    public function setScheme(string $scheme): Request
    {
        $this->scheme = $scheme;
        return $this;
    }

    public function getScheme(): string
    {
        return $this->scheme;
    }

    public function setHttpPort(int $httpPort): Request
    {
        $this->httpPort = $httpPort;
        return $this;
    }

    public function getHttpPort(): int
    {
        return $this->httpPort;
    }

    public function setHttpsPort(int $httpsPort): Request
    {
        $this->httpsPort = $httpsPort;
        return $this;
    }

    public function getHttpsPort(): int
    {
        return $this->httpsPort;
    }

    public function setQueryString(string $queryString): Request
    {
        $this->queryString = $queryString;
        return $this;
    }

    public function getQueryString(): ?string
    {
        return $this->queryString;
    }

    public function setPath(string $path): Request
    {
        if (isset($path)) {
            $pos = strpos($path, "?");
            if ($pos !== false) {
                $path = substr($path, 0, $pos);
            }
            $this->path = $path;
        }

        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function input(): InputManager
    {
        return InputManager::getInstance();
    }

    public function getCurrentUrl(bool $withQueryString = true): string
    {
        if (!$withQueryString && $this->queryString === null) {
            return Uri::build($this->getPath());
        }

        return Uri::build($this->getPath()) . "?" . $this->getQueryString();
    }

    /**
     * Validate inputs, throws validation exception on fail
     *
     * @param array $rules
     * @return void
     */
    public function validate(array $rules): void
    {
        $data = $this->input()->all()->{$this->method};
        $validation = new Validation;
        $validation->setData($data);
        $validation->setRules($rules);
        $validation->validate();
    }
}
