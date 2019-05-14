<?php

namespace App\Http\Controllers\Test;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Utils\Arrays;

class ArraysController extends Controller
{
    public function whitelist(Request $request): string
    {
        $input = [1,2,3,4,5,6,456,789,123,456,789,456,123,456];

        $whitelist = [1,3,5,7,9,11,13];

        $result = Arrays::whitelist($input, $whitelist);

        return fd_log_html(compact("input", "whitelist", "result"));
    }

    public function whitelistKeys(Request $request): string
    {
        $input = [
            "ichi" => 1,
            "ni" => 2,
            "san" => 3,
            "quattro" => "nope!",
            "go" => 5,
            "sei" => "nope!",
            "nana" => 7,

        ];

        $whitelist = [
            "ichi",
            "ni",
            "san",
            "shi",
            "go",
            "roku",
            "nana",
        ];

        $result = Arrays::whitelistKeys($input, $whitelist);

        return fd_log_html(compact("input", "whitelist", "result"));
    }

    public function defaults(Request $request): string
    {
        $input = [
            "this" => 11, // Override mandatory
            "friggin" => 55, // Override optional
            "test" => 66, // Override mandatory
        ];

        $defaults = [
            "this" => 1,
            "thing" => null,
            "is" => 3,
            "just" => 4,
            "a" => 5,
            "friggin" => null,
            "test" => 7,
        ];

        $result = Arrays::defaults($input, $defaults);

        return fd_log_html(compact("input", "defaults", "result"));
    }

    public function toObject(Request $request): string
    {
        $arr = [
            "someProp" => "foo",
            "bar" => [
                "someNestedProp" => "baz",
                "someNestedArray" => [1,2,3]
            ],
            "qux" => [4,5,6]
        ];

        $obj = Arrays::toObject($arr);

        return fd_log_html(compact("arr", "obj"), "arrayToObject");
    }
}
