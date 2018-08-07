<?php

namespace App\TinyHtmlMinifier;

use App\TinyHtmlMinifier\TinyHtmlMinifier;

class TinyMinify
{
    public static function html($html, $options = [])
    {
        $minifier = new TinyHtmlMinifier($options);
        return $minifier->minify($html);
    }
}
