<?php

namespace App\Http\Controllers\Test;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Services\Session;
use App\Views\Page;

class HomeController extends Controller
{
    public function index(Request $request): string
    {
        return (new Page)
            ->template('test/index')
            ->title('Test: index')
            ->variables([
                'urls' => Session::get('test-routes')
            ])
            ->minify(false)
            ->render();
    }
}
