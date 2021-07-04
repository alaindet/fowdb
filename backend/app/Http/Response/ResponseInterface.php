<?php

namespace App\Http\Response;

interface ResponseInterface
{
    public function setData(array $data): ResponseInterface;
    public function setHeader(string $name, string $value): ResponseInterface;
    public function setHeaders(array $headers): ResponseInterface;
    public function outputHeaders(): void;
    public function render(); // Can return several types or void
}
