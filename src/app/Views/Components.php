<?php

namespace App\Views;

class Components
{
    public const SIMPLE_COMPONENT = 42;

    public static $components = [
        'breadcrumb' => \App\Views\Components\Breadcrumb::class,
        'logout' => self::SIMPLE_COMPONENT,
        'pagination' => \App\Views\Components\Pagination::class,
        'progress-bar' => self::SIMPLE_COMPONENT,
        'form/input-clear' => self::SIMPLE_COMPONENT,
        'form/select-submit' => self::SIMPLE_COMPONENT,
        'top-anchor' => self::SIMPLE_COMPONENT,
    ];
}
