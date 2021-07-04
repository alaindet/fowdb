<?php

namespace App\Views\TinyHtmlMinifier;

use App\Views\TinyHtmlMinifier\TinyHtmlMinifier;

class TinyMinify
{
    public static function html($html, $options = [])
    {
        $minifier = new TinyHtmlMinifier($options);
        return $minifier->minify($html);
    }
}
