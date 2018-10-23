<?php

namespace App\Legacy;

class AdminView
{
    /**
     * Holds all links
     */
    public static $crumbs = ["Admin" => "/?p=admin"];
    
    /**
     * Outputs breadcrumbs to the page, 
     * Accepts an assoc array with label => link as elements
     *
     * @param array $crumbs
     * @param string $active
     * @return string
     */
    public static function breadcrumbs(array $crumbs = []): string
    {
        $output = '';

        $crumbs = array_merge(self::$crumbs, $crumbs);
        
        foreach ($crumbs as $label => $link) {
            ($link === '#')
                ? $output .= "<li>{$label}</li>"
                : $output .= "<li><a href=\"{$link}\">{$label}</a></li>";
        }

        return "<ol class=\"breadcrumb\">{$output}</ol>";
    }
}
