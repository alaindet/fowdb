<?php

namespace App\Utils;

class Time
{
    public static function yyyymmdd(): string
    {
        return date('Y-m-d');
    }

    public static function date(): string
    {
        return self::yyyymmdd();
    }
}
