<?php

namespace App\Http\Controllers;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Views\Page;

/**
 * Contains actions for PUBLIC routes only
 * Admin actions on cards are provided by ...\Admin\CardsController
 */
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
