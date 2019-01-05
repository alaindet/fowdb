<?php

namespace App\Utils;

class Strings
{
    /**
     * Converts a PascalCaseString into a kebab-case-string
     *
     * @param string $pascal
     * @return string
     */
    public static function pascalToKebab(string $pascal): string
    {
        return strtolower(preg_replace('/(?<!^)([A-Z])/', '-$1', $pascal));
    }

    /**
     * Converts a kebab-case-string into a PascalCaseString
     *
     * @param string $kebab
     * @return string
     */
    public static function kebabToPascal(string $kebab): string
    {
        return str_replace('-', '', ucwords($kebab, '-'));
    }

    /**
     * Converts a kebab-case-string into a camelCaseString
     *
     * @param string $kebab
     * @return string
     */
    public static function kebabToCamel(string $kebab): string
    {
        $string = str_replace('-', '', ucwords($kebab, '-'));
        return strtolower($string[0]) . substr($string, 1);
    }

    /**
     * Converts a kebab-case-string into a snake_case_string
     *
     * @param string $kebab
     * @return string
     */
    public static function kebabToSnake(string $kebab): string
    {
        return str_replace('-', '_', $kebab);
    }

    /**
     * Converts a snake_case_string into a Title Case String
     *
     * @param string $snake
     * @return string
     */
    public static function snakeToTitle(string $snake): string
    {
        return ucwords(str_replace('_', ' ', $snake));
    }
}
