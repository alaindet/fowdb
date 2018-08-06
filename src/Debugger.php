<?php

namespace App;

class Debugger
{
    /**
     * Logs content of $x using internal print_r($x,true) function and <pre> tag
     *
     * @param string $title of the log
     * @param mixed $x object or array to log
     * @return string HTML content to be output
     */
    public static function log($x = null, $title = '')
    {
        // Default separator
        $separator = '';

        // Check if there's a title for log
        if (!empty($title)) {
            // Build separator
            while (strlen($separator) < strlen($title)) { $separator.='='; }
            $separator = "<br>".$separator."<br>";
        }

        // Build content
        $content = print_r($x, true);

        // Return log
        return "<pre>{$title}{$separator}{$content}</pre>";
    }
}
