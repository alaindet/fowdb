<?php

namespace App\Views;

use \App\Views\Components\Pagination;
use \App\Views\Components\InputDropdown;
use \App\Views\Components\Breadcrumb;

class Components
{
    public const SIMPLE_COMPONENT = 42;

    public static $components = [
        'breadcrumb'                  => Breadcrumb::class,
        'form/button-checkbox'        => self::SIMPLE_COMPONENT,
        'form/button-checkboxes'      => self::SIMPLE_COMPONENT,
        'form/button-radio'           => self::SIMPLE_COMPONENT,
        'form/button-dropdown'        => self::SIMPLE_COMPONENT,
        'form/input-clear'            => self::SIMPLE_COMPONENT,
        'form/input-dropdown'         => InputDropdown::class,
        'form/select-multiple-handle' => self::SIMPLE_COMPONENT,
        'form/select-multiple-items'  => self::SIMPLE_COMPONENT,
        'form/select-submit'          => self::SIMPLE_COMPONENT,
        'logout'                      => self::SIMPLE_COMPONENT,
        'pagination'                  => Pagination::class,
        'top-anchor'                  => self::SIMPLE_COMPONENT,
    ];
}
