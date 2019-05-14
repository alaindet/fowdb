<?php

namespace App\Http\Response;

use App\Utils\Uri;

class Redirect
{
    /**
     * Redirects to a given location
     *
     * @param string $uri
     * @param array|object $qs
     * @return void
     */
    static public function to(string $uri = "", $queryString = null): void
    {
        $uri = Uri::build($uri, $queryString);
        header("Location: {$uri}");
        die();
    }

    /**
     * Redirects to an absolute URI
     * Useful for some application logic and external links
     *
     * @param string $absoluteUri
     * @return void
     */
    static public function toAbsoluteUrl(string $absoluteUri): void
    {
        header("Location: {$absoluteUri}");
        die();
    }

    /**
     * Redirects to the previous page
     *
     * @return void
     */
    static public function back(): void
    {
        self::to($_SERVER["HTTP_REFERER"] ?? "/");
    }
}
