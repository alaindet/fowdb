<?php

namespace App\Utils;

abstract class Logger
{
    /**
     * Logs content of $data using internal print_r($data,true) function
     * And <pre> tag
     *
     * @param any $data
     * @param string $title of the log
     * @param bool $wrap Whether to wrap text or not
     * @return string HTML content to be output
     */
    public static function html(
        $data,
        string $title = null,
        bool $wrap = false
    ): string
    {
        // Line
        $line = "";
        if (isset($title)) {
            while (strlen($line) < strlen($title)) $line .= "=";
            $line = "<br>{$line}<br>";
        }

        // Content
        if (is_bool($data)) $content = "(bool) " . ($data ? "TRUE" : "FALSE");
        elseif (is_string($data)) $content = $data;
        else $content = print_r($data, true);

        // Wrap?
        $style = ($wrap) ? " style=\"white-space: pre-wrap;\"" : "";

        // Return log
        return "<pre{$style}>{$title}{$line}{$content}</pre>";
    }

    /**
     * Logs $data using print_r() in CLI-friendly format
     *
     * @param mixed $data Can be anything, usually it"s an array
     * @param string $title Output title
     * @return string Content to be output
     */
    public static function cli($data, string $title = null): string
    {
        // Line
        $line = "";
        if (isset($title)) {
            while (strlen($line) < strlen($title)) $line .= "=";
            $line = "\n{$line}\n";
        }

        // Content
        if (is_bool($data)) $content = "(bool) " . ($data ? "TRUE" : "FALSE");
        elseif (is_string($data)) $content = $data;
        else $content = print_r($data, true);

        // Return log
        return "\n\n{$title}{$line}{$content}\n\n";
    }
}
