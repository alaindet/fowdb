<?php

namespace App\Http\Request;

use App\Http\Request\Input;
use App\Services\Alert;
use App\Services\Validation\Validation;
use App\Exceptions\ValidationException;
use App\Exceptions\ApiValidationException;

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
     * Sets and reads app data
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

        return $this->app[$name];
    }

    public function input(): Input
    {
        return Input::getInstance();
    }

    public function getCurrentUrl(bool $withQueryString = false): string
    {
        // $currentUrl = url($this->path);

        // if (!$withQueryString) return $currentUrl;

        // $qs = !empty($this->queryString) ? '?'.$this->queryString : '';
        // return $currentUrl . $qs;

        $queryString = !empty($this->queryString) ? '?'.$this->queryString : '';
        return url($this->path) . $queryString;
    }

    /**
     * Activates JSON validation errors (used in API)
     *
     * @return Request
     */
    public function api(): Request
    {
        $this->validationException = ApiValidationException::class;

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
    public function validate(
        string $httpMethod,
        array $rules,
        array $input = null
    ): void
    {
        $errors = (new Validation)
            ->input($input ?? $this->input()->$httpMethod())
            ->validate($rules)
            ->getErrors();

        // No validation errors, quit here
        if (empty($errors)) return;

        // Build the error message
        if (count($errors) === 1) {
            $message = $errors[0];
        } else {
            $message = "<ul class='display-inline-block'>";
            foreach ($errors as $error) $message .= "<li>{$error}</li>";
            $message .= "</ul>";
        }

        // Throw a validation error
        throw new $this->validationException($message);
    }
}
