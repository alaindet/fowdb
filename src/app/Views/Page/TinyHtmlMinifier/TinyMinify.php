<?php

namespace App\Views\Page\TinyHtmlMinifier;

use App\Views\Page\TinyHtmlMinifier\TinyHtmlMinifier;

class TinyMinify
{
    public static function html($html, $options = [])
    {
        $minifier = new TinyHtmlMinifier($options);
        return $minifier->minify($html);
    }
}
