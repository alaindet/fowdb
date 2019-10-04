<?php

namespace App\Utils;

use App\Services\Configuration\Configuration;

abstract class Uri
{
    /**
     * Assembles an absolute URL, with an optional query string
     * 
     * @param string $uri The relative URI
     * @param array|object $qs The query string assoc array or object
     */
    public static function build(string $uri = "", $qs = null): string
    {
        // Client wants to go back
        if ($uri === "back") {

            // Check if we can go back
            if (isset($_SERVER["HTTP_REFERER"])) {
                return $_SERVER["HTTP_REFERER"];
            }
            
            // No referer is set, go to homepage
            $uri = "/";
        }

        // Base URL for this website
        $baseUrl = (Configuration::getInstance())->get("app.url");

        // Build the query string
        $queryString = self::buildQueryString($qs);

        // Remove unwanted left slash
        $uri = ltrim($uri, "/");

        // Build final URI
        return "{$baseUrl}/{$uri}{$queryString}";
    }

    /**
     * Builds a query string from an associative array or object
     * Supports parameters with multiple values
     * 
     * Ex.:
     * 
     * INPUT
     * $qs = [
     *   "id" => 123,
     *   "set" => [ "set1", "set2" ],
     * ];
     * 
     * OUTPUT
     * ?id=123&set[]=set1&set[]=set2
     *
     * @param array|object $params
     * @return string
     */
    public static function buildQueryString($params = null): string
    {
        // No parameters
        if ($params === null || $params === []) {
            return "";
        }

        return "?".http_build_query($params);
    }

    /**
     * Removes a query string
     * Ex.: Read a card"s image_path, remove qs, then remove it from filesystem
     *
     * @param string $uri
     * @return string
     */
    public static function removeQueryString(string $uri): string
    {
        $uri = ltrim($uri, "/");
        $pos = strpos($uri, "?");
        if (false !== $pos) {
            $uri = substr($uri, 0, $pos);
        }
        return $uri;
    }

    /**
     * Remove a small portion of a query string
     *
     * @param string $uri The URI to process
     * @param string|array $removeTheseParams One or a list of parameters
     * @return string
     */
    public static function removeQueryStringParameter(
        string $uri,
        $removeTheseParams
    ): string
    {
        // Split base URI from query string and hash fragment
        $splitByQuestionMark = explode("?", $uri);

        // No query string, return as it is
        if (!isset($splitByQuestionMark[1])) {
            return $uri;
        }

        [$baseUri, $queryString] = $splitByQuestionMark;

        // Remove the hash fragment
        $splitByHash = explode("#", $queryString);

        (isset($splitByHash[1]))
            ? [$queryString, $fragment] = $splitByHash
            : [$queryString, $fragment] = [$splitByHash[0], ""];

        // Read query string and extract current parameters
        $currentParams = [];
        parse_str($queryString, $currentParams);

        // Normalize input parameters as array
        if (!is_array($removeTheseParams)) {
            $removeTheseParams = [$removeTheseParams];
        }

        // Remove all unwanted parameters
        foreach ($removeTheseParams as $removeThisParam) {
            if (isset($currentParams[$removeThisParam])) {
                unset($currentParams[$removeThisParam]);
            }
        }

        $queryString = http_build_query($currentParams);
        $result = $baseUri;

        if ($queryString !== "") {
            $result .= "?{$queryString}";
        }

        if ($fragment !== "") {
            $result .= "#{$fragment}";
        }

        return $result;
    }

    /**
     * Creates or overwrites a query string parameter
     * 
     * If $paramValue is a callback, it will receive the old value as input (or
     * null if there's no old value) and the returned value is then used as
     * the new value for given parameter
     * 
     * Ex.:
     * Uri::setQueryStringParameter(
     *     'hello/world?page=42',
     *     'page',
     *     function ($page = null) {
     *         $page = $page ?? 0;
     *         return $page + 1;
     *     }
     * );
     *
     * @param string $uri
     * @param string $paramName
     * @param string|string[]|callable $paramValue
     * @return string
     */
    public static function setQueryStringParameter(
        string $uri,
        string $paramName,
        $paramValue
    ): string
    {
        $splitByQuestionMark = explode("?", $uri);
        $baseUri = $splitByQuestionMark[0];
        $queryString = $splitByQuestionMark[1] ?? "";

        // Explodes the querys tring into an array
        $currentParams = [];
        parse_str($queryString, $currentParams);
        
        if (is_callable($paramValue)) {
            $newValue = $paramValue($currentParams[$paramName] ?? null);
            $newParams[$paramName] = $newValue;
        } else {
            $newParams[$paramName] = $paramValue;
        }

        // Implodes back an array as a query string
        $queryString = http_build_query($newParams);
        
        return "{$baseUri}?{$queryString}";
    }
}
