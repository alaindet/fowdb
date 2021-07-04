<?php

namespace App\Base;

trait BaseTrait
{
    /**
     * Gets the short name (without namespace) of the child class
     * Works on dynamic and static classes as well
     *
     * @return string
     */
    public static function getShortName(): string
    {
        $class = get_called_class();
        return substr($class, strrpos($class, '\\') + 1);
    }
}
