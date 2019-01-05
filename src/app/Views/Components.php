<?php

namespace App\Views;

class Components
{
    public const SIMPLE_COMPONENT = 42;

    public static $components = [
        'breadcrumb' => \App\Views\Components\Breadcrumb::class,
        'form/button-checkboxes' => self::SIMPLE_COMPONENT,
        'form/button-dropdown' => self::SIMPLE_COMPONENT,
        'form/input-clear' => self::SIMPLE_COMPONENT,
        'form/input-dropdown' => \App\Views\Components\InputDropdown::class,
        'form/select-multiple-handle' => self::SIMPLE_COMPONENT,
        'form/select-multiple-items' => self::SIMPLE_COMPONENT,
        'form/select-submit' => self::SIMPLE_COMPONENT,
        'logout' => self::SIMPLE_COMPONENT,
        'pagination' => \App\Views\Components\Pagination::class,
        'progress-bar' => self::SIMPLE_COMPONENT,
        'top-anchor' => self::SIMPLE_COMPONENT,
    ];
}
