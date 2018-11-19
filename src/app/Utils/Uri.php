<?php

namespace App\Utils;

class Uri
{
    /**
     * Assembles an absolute URL, with an optional query string
     * 
     * @param string $uri The relative URI
     * @param array $qs The query string assoc array ( name => value(s) )
     */
    public static function build(string $uri = '', array $qs = []): string
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

        // Base URL for this website
        $baseUrl = config('app.url');

        // Build the query string
        $queryString = self::buildQueryString($qs);

        // Remove unwanted left slash
        $uri = ltrim($uri, '/');

        // Build final URI
        return "{$baseUrl}/{$uri}{$queryString}";
    }

    /**
     * Builds a query string from an associative array
     * Supports parameters with multiple values
     * 
     * Ex.:
     * 
     * INPUT
     * $qs = [
     *   'id' => 123,
     *   'set' => [ 'set1', 'set2' ],
     * ];
     * 
     * OUTPUT
     * ?id=123&set[]=set1&set[]=set2
     *
     * @param array $qs
     * @return string
     */
    public static function buildQueryString(array $queryString = []): string
    {
        // Missing query string
        if (empty($queryString)) return '';

        $result = [];

        foreach ($queryString as $key => $values) {

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

    /**
     * Removes a query string
     * Ex.: Read a card's image_path, remove qs, then remove it from filesystem
     *
     * @param string $uri
     * @return string
     */
    public static function removeQueryString(string $uri): string
    {
        $uri = ltrim($uri, '/');
        $pos = strpos($uri, '?');
        if (false !== $pos) $uri = substr($uri, 0, $pos);
        return $uri;
    }

    /**
     * Remove a small portion of a query string
     *
     * @param string $uri The URI to process
     * @param string|array $parameters One or a list of parameters
     * @return string
     */
    public static function removeQueryStringParameter(
        string $uri,
        $parameters
    ): string
    {
        // Split base URI from query string and hash fragment
        $bits = explode('?', $uri);

        // No query string, return as it is
        if (!isset($bits[1])) return $uri;

        [$baseUri, $queryString] = $bits;

        // Remove the hash fragment
        $bits = explode('#', $queryString);

        if (!isset($bits[1])) $bits[1] = '';

        [$queryString, $fragment] = $bits;

        // Parse query string as array
        parse_str($queryString, $qsParameters);

        // Normalize input parameters as array
        if (!is_array($parameters)) $parameters = [$parameters];

        // Remove all unwanted parameters
        foreach ($parameters as $parameter) {
            if (isset($qsParameters[$parameter])) {
                unset($qsParameters[$parameter]);
            }
        }

        // Re-build purged query string
        $queryString = http_build_query($qsParameters);

        return $baseUri
            . ($queryString !== '' ? "?{$queryString}" : '')
            . ($fragment !== '' ? "#{$fragment}" : '');
    }
}
