<?php

namespace App\Utils;

class Strings
{
    static public function pascalToKebab(string $pascal): string
    {
        return strtolower(preg_replace('/(?<!^)([A-Z])/', '-$1', $pascal));
    }

    static public function kebabToPascal(string $kebab): string
    {
        return str_replace('-', '', ucwords($kebab, '-'));
    }

    static public function snakeToTitle(string $snake): string
    {
        return ucwords(str_replace('_', ' ', $snake));
    }

    static public function endsWith(string $what, string $with): bool
    {
        return substr($what, -strlen($with)) === $with;
    }
}
