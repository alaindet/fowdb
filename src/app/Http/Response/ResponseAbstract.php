<?php

namespace App\Http\Response;

use App\Http\Response\ResponseInterface;

abstract class ResponseAbstract implements ResponseInterface
{
    /**
     * Holds data to be used by render()
     *
     * @var array|object
     */
    protected $data;

    /**
     * Holds all the headers to be output
     *
     * @var array
     */
    protected $headers = [];

    /**
     * Sets a single HTTP header
     *
     * @param string $name
     * @param string $value
     * @return ResponseInterface
     */
    public function setHeader(string $name, string $value): ResponseInterface
    {
        $this->headers[$name] = $value;

        return $this;
    }

    /**
     * Sets multiple HTTP headers at once
     *
     * @param array $headers HTTP headers [name => value]
     * @return ResponseInterface
     */
    public function setHeaders(array $headers): ResponseInterface
    {
        foreach ($headers as $name => $value) {
            $this->headers[$name] = $value;
        }

        return $this;
    }

    /**
     * Outputs headers to the response
     *
     * @param array $headers
     * @return ResponseInterface
     */
    public function outputHeaders(): void
    {
        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }
    }

    /**
     * Sets custom data to be used inside render()
     *
     * @param array|object $data
     * @return ResponseInterface
     */
    public function setData($data): ResponseInterface
    {
        $this->data = $data;

        return $this;
    }
}
