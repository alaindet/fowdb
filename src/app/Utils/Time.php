<?php

namespace App\Utils;

class Time
{
    public static function date(): string
    {
        return date('Y-m-d');
    }

    public static function timestamp(string $format = 'default'): string
    {
        $formats = [
            'default' => 'Y-m-d H:i:s',
            'file' => 'Ymd_His',
            'file-nospace' => 'YmdHis',
        ];

        return date($formats[$format]);
    }
}
