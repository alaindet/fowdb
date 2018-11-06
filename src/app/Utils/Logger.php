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
    public static function html($data, string $title = null): string
    {
        // Line
        $line = '';
        if (isset($title)) {
            while (strlen($line) < strlen($title)) $line .= '=';
            $line = "<br>{$line}<br>";
        }

        // Content
        if (is_bool($data)) $content = '(bool) ' . ($data ? 'TRUE' : 'FALSE');
        elseif (is_string($data)) $content = $data;
        else $content = print_r($data, true);

        // Return log
        return "<pre>{$title}{$line}{$content}</pre>";
    }

    /**
     * Logs $data using print_r() in CLI-friendly format
     *
     * @param mixed $data Can be anything, usually it's an array
     * @param string $title Output title
     * @return string Content to be output
     */
    public static function cli($data, string $title = null): string
    {
        // Line
        $line = '';
        if (isset($title)) {
            while (strlen($line) < strlen($title)) $line .= '=';
            $line = "\n{$line}\n";
        }

        // Content
        if (is_bool($data)) $content = '(bool) ' . ($data ? 'TRUE' : 'FALSE');
        elseif (is_string($data)) $content = $data;
        else $content = print_r($data, true);

        // Return log
        return "\n\n{$title}{$line}{$content}\n\n";
    }
}
