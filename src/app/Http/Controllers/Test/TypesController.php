<?php

namespace App\Http\Controllers\Test;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Utils\Json;
use App\Utils\Objects;
use App\Utils\Arrays;

class TypesController extends Controller
{
    private $array;
    private $json;
    private $object;

    public function __construct()
    {
        $this->array = [
            "foo" => 10,
            "bars" => [21, 22],
            "baz" => 30,
        ];

        $this->json = '{"foo":10,"bars":[21,22],"baz":30}';

        $this->object = new \stdClass();
        $this->object->foo = 10;
        $this->object->bars = [21, 22];
        $this->object->baz = 30;
    }

    public function arrayToObject(Request $request): string
    {
        $new = Objects::fromArray($this->array);
        $new = Arrays::toObject($this->array);
        return fd_log_html($new, "array-to-object");
    }

    public function arrayToJson(Request $request): string
    {
        $new = Json::fromArray($this->array);
        $new = Arrays::toJson($this->array);
        return fd_log_html($new, "array-to-json");
    }

    public function objectToArray(Request $request): string
    {
        $new = Arrays::fromObject($this->object);
        $new = Objects::toArray($this->object);
        return fd_log_html($new, "object-to-array");
    }

    public function objectToJson(Request $request): string
    {
        $new = Json::fromObject($this->object);
        $new = Objects::toJson($this->object);
        return fd_log_html($new, "object-to-json");
    }

    public function jsonToArray(Request $request): string
    {
        $new = Arrays::fromJson($this->json);
        $new = Json::toArray($this->json);
        return fd_log_html($new, "json-to-array");
    }

    public function jsonToObject(Request $request): string
    {
        $new = Objects::fromJson($this->json);
        $new = Json::toObject($this->json);
        return fd_log_html($new, "json-to-object");
    }
}
