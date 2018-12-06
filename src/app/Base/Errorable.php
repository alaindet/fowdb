<?php

namespace App\Base;

/**
 * This is a stack (LIFO) implementation or an error bag
 * When added to any class, it provides methods to add multiple errors
 * in a stack without halting the execution. Useful with validation rules
 */
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
