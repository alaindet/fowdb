<?php

namespace App\Utils;

use App\Utils\Arrays;
use App\Utils\Objects;

abstract class Json
{
    static public function fromObject(object $object): string
    {
        return json_encode(
            $object,
            JSON_UNESCAPED_SLASHES |
            JSON_UNESCAPED_UNICODE |
            JSON_PARTIAL_OUTPUT_ON_ERROR |
            JSON_PRESERVE_ZERO_FRACTION |
            JSON_UNESCAPED_LINE_TERMINATORS
        );
    }

    static public function toObject(string $json): object
    {
        return Objects::fromJson($json);
    }

    static public function fromArray(array $array): string
    {
        return json_encode(
            $array,
            JSON_UNESCAPED_SLASHES |
            JSON_UNESCAPED_UNICODE |
            JSON_PARTIAL_OUTPUT_ON_ERROR |
            JSON_PRESERVE_ZERO_FRACTION |
            JSON_UNESCAPED_LINE_TERMINATORS
        );
    }

    static public function toArray(string $json): array
    {
        return Arrays::fromJson($json);
    }
}
