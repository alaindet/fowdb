<?php

namespace App\Utils;

class Logger
{
    /**
     * Logs content of $x using internal print_r($x,true) function and <pre> tag
     *
     * @param string $title of the log
     * @param mixed $x object or array to log
     * @return string HTML content to be output
     */
    public static function html($x = null, $title = ''): string
    {
        // Line
        $line = '';
        if (!empty($title)) {
            while (strlen($line) < strlen($title)) $line .= '=';
            $line = "<br>{$line}<br>";
        }

        // Content
        $content = print_r($x, true);

        // Return log
        return "<pre>{$title}{$line}{$content}</pre>";
    }
}
