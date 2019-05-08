<?php

namespace App\Utils;

abstract class Json
{
    static private $encodeOptions = (
        JSON_UNESCAPED_SLASHES |
        JSON_UNESCAPED_UNICODE |
        JSON_PARTIAL_OUTPUT_ON_ERROR |
        JSON_PRESERVE_ZERO_FRACTION |
        JSON_UNESCAPED_LINE_TERMINATORS
    );

    static public function fromObject(object $object): string
    {
        return json_encode($object, self::$encodeOptions);
    }

    static public function toObject(string $json): object
    {
        return json_decode($json, $assoc = false);
    }

    static public function fromArray(array $array): string
    {
        return json_encode($array, self::$encodeOptions);
    }

    static public function toArray(string $json): array
    {
        return json_decode($json, $assoc = true);
    }
}
