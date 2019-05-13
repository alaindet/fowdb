<?php

namespace App\Utils;

use App\Utils\Json;
use App\Utils\Arrays;

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

    static public function fromArray(
        array $array,
        bool $deepClone = true
    ): object
    {
        if ($deepClone) {
            return self::fromJson(Json::fromArray($array));
        }

        return (object) $array;
    }

    static public function toArray(object $object): array
    {
        return Arrays::fromObject($object);
    }

    static public function fromJson(string $json): object
    {
        return json_decode($json, $assoc = false);
    }

    static public function toJson(object $object): string
    {
        return Json::fromObject($object);
    }
}
