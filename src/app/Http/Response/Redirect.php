<?php

namespace App\Http\Response;

use App\Services\Config;
use App\Utils\Uri;

class Redirect
{
    /**
     * Redirects to a given location
     *
     * @param string $uri
     * @param array $qs
     * @return void
     */
    public function to(string $uri = '', array $queryString = []): void
    {
        header('Location: ' . Uri::build($uri, $queryString));
        die();
    }

    /**
     * Redirects to an absolute URI
     * Useful for some application logic and external links
     *
     * @param string $absoluteUri
     * @return void
     */
    public function toAbsoluteUrl(string $absoluteUri): void
    {
        header('Location: ' . $absoluteUri);
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
}
