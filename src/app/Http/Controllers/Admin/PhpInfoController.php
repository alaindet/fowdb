<?php

namespace App\Http\Controllers\Admin;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Views\Page\Page;

class PhpInfoController extends Controller
{
    public function showPhpInfo(): string
    {
        ob_start();
        phpinfo();
        return ob_get_clean();
    }

}
