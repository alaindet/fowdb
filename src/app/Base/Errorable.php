<?php

namespace App\Base;

trait Errorable
{
    private $errors = [];

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function pushError(string $message): void
    {
        $this->errors[] = $message;
    }

    public function popError(): string
    {
        return array_pop($this->errors);
    }

    public function clearErrors(): void
    {
        $this->errors = [];
    }
}
