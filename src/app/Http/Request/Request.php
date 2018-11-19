<?php

namespace App\Http\Request;

use App\Base\GetterSetterTrait;
use App\Http\Request\Input;
use App\Services\Alert;
use App\Services\Validation\Validation;
use App\Exceptions\ValidationException;

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

    public function getCurrentUrl($withQueryString = true): string
    {
        $queryString = !empty($this->queryString) ? '?'.$this->queryString : '';
        return url($this->path) . $queryString;
    }

    public function validate(
        string $type,
        array $toValidate,
        array $input = null
    ): void
    {
        $errors = (new Validation)
            ->input($input ?? $this->input()->$type())
            ->validate($toValidate)
            ->getErrors();

        // Build the error message
        if (!empty($errors)) {

            // Single error
            if (count($errors) === 1) {
                $message = $errors[0];
            }
            
            // Errors list
            else {
                $message = collapse(
                    "<ul style='display:inline-block'>",
                    array_reduce($errors, function ($message, $error) {
                        return $message .= "<li>{$error}</li>";
                    }),
                    "</ul>"
                );
            }

            throw new ValidationException($message);
        }
    }
}
