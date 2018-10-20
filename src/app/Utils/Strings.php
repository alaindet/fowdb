<?php

namespace App\Utils;

class Strings
{
    public static function pascalToKebab(string $pascal): string
    {
        return strtolower(preg_replace('/(?<!^)([A-Z])/', '-$1', $pascal));
    }

    public static function kebabToPascal(string $kebab): string
    {
        return str_replace(' ', '',
            ucwords(
                str_replace(['-', '_'], ' ', $kebab)
            )
        );
    }
}
