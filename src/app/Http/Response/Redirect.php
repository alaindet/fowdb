<?php

namespace App\Http\Response;

use App\Services\Config;

class Redirect
{
    /**
     * Redirects to a given location
     *
     * @param string $uri
     * @param array $qs
     * @return void
     */
    public function to(string $uri = '', array $qs = []): void
    {
        header('Location: ' . self::url($uri, $qs));
        die();
    }

    /**
     * Redirects to the previous page
     *
     * @return void
     */
    public static function back(): void
    {
        self::to($_SERVER['HTTP_REFERER'] ?? '/');
    }

    /**
     * Assembles an absolute URL, with an optional query string
     * 
     * @param string $uri The relative URI
     * @param array $qs The query string assoc array ( name => value(s) )
     */
    public static function url(string $uri = '', array $qs = []): string
    {
        // Client wants to go back
        if ($uri === 'back') {

            // Check if we can go back
            if (isset($_SERVER['HTTP_REFERER'])) {
                return $_SERVER['HTTP_REFERER'];
            }
            
            // No referer is set, go to homepage
            $uri = '/';
        }

        $baseUrl = (Config::getInstance())->get('app.url');
        $queryString = self::parseQueryString($qs);
        if ($uri[0] === '/') $uri = substr($uri, 1);
        return "{$baseUrl}/{$uri}{$queryString}";
    }

    /**
     * Parses a query string associative array
     * Supports parameters with multiple values
     * 
     * Ex.:
     * $qs = [
     *   'id' => 123,
     *   'set' => [ 'set1', 'set2' ],
     * ];
     *
     * @param array $qs
     * @return string
     */
    private static function parseQueryString(array $qs = []): string
    {
        // Missing query string
        if (empty($qs)) return '';

        $result = [];

        foreach ($qs as $key => $values) {

            // Multiple values
            if (is_array($values)) {
                $partial = [];
                foreach ($values as $value) $partial[] = "{$key}[]={$value}";
                $result[] = implode('&', $partial);
            }
            
            // Single value
            else {
                $result[] = "{$key}={$values}";
            }
        }

        return '?'.implode('&', $result);
    }
}
