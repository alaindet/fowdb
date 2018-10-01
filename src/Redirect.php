<?php

namespace App;

class Redirect
{
    public static function to(string $url = '', array $params = [])
    {
        header('Location: '.self::url($url, $params));
        exit();
    }

    public static function url(string $url = '', array $params = [])
    {
        $page = ($url === '' || $url === '/') ? [] : ['p' => $url];
        $params = array_merge($params, $page);
        $qs = self::queryString($params);
        return "/index.php{$qs}";
    }

    private static function queryString(array $params = null)
    {
        $result = [];
        foreach ($params as $key => &$value) {
            if (is_array($value)) $result[] = self::parseArray($key, $value);
            else $result[] = "{$key}={$value}";
        }
        return empty($result) ? '' : '?'.implode('&', $result);
    }

    private static function parseArray(string $name, array $values)
    {   
        return implode('&', array_map(function ($value) use ($name) {
            return "{$name}[]={$value}";    
        }, $values));
    }
}
