<?php

namespace App\Http\Controllers;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Views\Page;
use App\Legacy\Authorization;

class HomeController extends Controller
{
    /**
     * Legacy, TO DO
     *
     * @return string
     */
    public function show(): string
    {
        return view_old(
            'Search',
            'old/search/search.php',
            [ 'js' => [ 'public/search' ] ],
            ['thereWereResults' => false]
        );
    }
}
