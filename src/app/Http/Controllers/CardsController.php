<?php

namespace App\Http\Controllers;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Views\Page;

class CardsController extends Controller
{
    public function showSearchHelp(): string
    {
        return (new Page)
            ->template('pages/public/cards/search-help')
            ->title('Cards Search Help')
            ->render();
    }
}
