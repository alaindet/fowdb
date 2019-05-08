<?php

namespace App\Utils;

abstract class Strings
{
    /**
     * Converts a PascalCaseString into a kebab-case-string
     *
     * @param string $pascal
     * @return string
     */
    public static function pascalToKebab(string $pascal): string
    {
        return strtolower(preg_replace("/(?<!^)([A-Z])/", "-$1", $pascal));
    }

    /**
     * Converts a kebab-case-string into a PascalCaseString
     *
     * @param string $kebab
     * @return string
     */
    public static function kebabToPascal(string $kebab): string
    {
        return str_replace("-", "", ucwords($kebab, "-"));
    }

    /**
     * Converts a kebab-case-string into a camelCaseString
     *
     * @param string $kebab
     * @return string
     */
    public static function kebabToCamel(string $kebab): string
    {
        $string = str_replace("-", "", ucwords($kebab, "-"));
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
        return str_replace("-", "_", $kebab);
    }

    /**
     * Converts a snake_case_string into a Title Case String
     *
     * @param string $snake
     * @return string
     */
    public static function snakeToTitle(string $snake): string
    {
        return ucwords(str_replace("_", " ", $snake));
    }

    /**
     * Turns any string into snake_case
     * Optionally turns everything to lower case (default)
     *
     * @param string $input
     * @param bool $lowercase
     * @return string
     */
    public static function toSnake(
        string $input,
        bool $lowercase = true
    ): string
    {
        // Any whitespace to _, remove all non-alphanumeric characters
        return preg_replace(
            [
                "/[\s]/",
                "/[^_a-zA-Z0-9]/"
            ],
            [
                "_",
                ""
            ],
            $lowercase ? strlower($input) : $input
        );
    }
}
