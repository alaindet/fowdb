<?php

namespace App\Utils;

use App\Utils\Json;

abstract class Objects
{
    static public function clone(object $obj): object
    {
        return clone $obj;
    }

    static public function deepClone(object $obj): object
    {
        return Json::toObject(Json::fromObject($obj));
    }
}
