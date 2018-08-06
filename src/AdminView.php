<?php

namespace App;

class AdminView
{
    /**
     * Holds all links
     */
    public static $crumbs = ["Admin Menu" => "/index.php?p=admin"];
    
    /**
     * Outputs breadcrumbs to the page, 
     * Accepts an assoc array with label => link as elements
     *
     * @param array $crumbs
     * @param string $active
     * @return string
     */
    public static function breadcrumbs($crumbs = null)
    {
        $output = "<ol class='breadcrumb'>";

        if (isset($crumbs)) {
            $crumbs = array_merge(self::$crumbs, $crumbs);
        }
        
        foreach ($crumbs as $label => $link) {
            $output .= "<li><a href='{$link}'>{$label}</a></li>";
        }

        return $output . "</ol>";
    }
}
