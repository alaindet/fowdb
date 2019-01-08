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
        $raw = [1,2,3,4,5,6,456,789,123,456,789,456,123,456];
        $whitelist = [1,3,5,7,9,11,13];

        return log_html(
            Arrays::filterWhitelist($raw, $whitelist)
        );
    }
}
