<?php

namespace App\Http\Controllers\Test;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Views\Page;
use App\Utils\Arrays;

class UtilsController extends Controller
{
    public function arrayWhitelist(Request $request): string
    {
        $input = [1,2,3,4,5,6,456,789,123,456,789,456,123,456];

        $whitelist = [1,3,5,7,9,11,13];

        $result = Arrays::whitelist($input, $whitelist);

        return log_html(compact('input', 'whitelist', 'result'));
    }

    public function arrayWhitelistKeys(Request $request): string
    {
        $input = [
            'ichi' => 1,
            'ni' => 2,
            'san' => 3,
            'quattro' => 'nope!',
            'go' => 5,
            'sei' => 'nope!',
            'nana' => 7,

        ];

        $whitelist = [
            'ichi',
            'ni',
            'san',
            'shi',
            'go',
            'roku',
            'nana',
        ];

        $result = Arrays::whitelistKeys($input, $whitelist);

        return log_html(compact('input', 'whitelist', 'result'));
    }

    public function arrayDefaults(Request $request): string
    {
        $input = [
            'this' => 11, // Override mandatory
            'friggin' => 55, // Override optional
            'test' => 66, // Override mandatory
        ];

        $defaults = [
            'this' => 1,
            'thing' => null,
            'is' => 3,
            'just' => 4,
            'a' => 5,
            'friggin' => null,
            'test' => 7,
        ];

        $result = Arrays::defaults($input, $defaults);

        return log_html(compact('input', 'defaults', 'result'));
    }
}
