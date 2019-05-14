<?php

namespace App\Http\Controllers\Test;

use App\Base\Controller;
use App\Http\Request\Request;

class InputController extends Controller
{
    public function all(Request $request): string
    {
        $input = $request->input()->all();
        return fd_log_html($input, "input/all");
    }

    public function exists(Request $request, string $key): string
    {
        $isParam = $request->input()->has($key, "GET");
        $isParam = $request->input()->get($key) !== null;
        return "GET Parameter \"{$key}\" is ".($isParam ? "" : "not ")."set";
    }
}
