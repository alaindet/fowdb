<?php

namespace App\Legacy;

class Redirect
{
    public static function to(string $page = '', array $params = [])
    {
        header('Location: '.self::url($page, $params));
        die();
    }
    
    public static function back(): void
    {
        self::to($_SERVER['HTTP_REFERER'] ?? '/');
    }

    public static function url(string $page = '', array $params = []): string
    {
        $page = ($page === '' || $page === '/') ? [] : ['p' => $page];
        $params = array_merge($page, $params);
        $qs = self::queryString($params);
        $baseUrl = config('app.url');
        return "{$baseUrl}/{$qs}";
    }

    private static function queryString(array $params = null): string
    {
        $result = [];
        foreach ($params as $key => &$value) {
            if (is_array($value)) $result[] = self::parseArray($key, $value);
            else $result[] = "{$key}={$value}";
        }
        return empty($result) ? '' : '?'.implode('&', $result);
    }

    private static function parseArray(string $name, array $values): string
    {   
        return implode('&', array_map(function ($value) use ($name) {
            return "{$name}[]={$value}";    
        }, $values));
    }
}
