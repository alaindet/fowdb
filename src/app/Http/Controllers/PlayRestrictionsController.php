<?php

namespace App\Http\Controllers;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Views\Page;
use App\Models\PlayRestriction as Model;
use App\Views\PlayRestriction as View;
use App\Http\Response\Redirect;
use App\Services\Alert;

class PlayRestrictionsController extends Controller
{
    public function index(Request $request): string
    {
        $rawData = Model::getData($page = 'banlist');
        $items = View::display($rawData, 'all');

        // ERROR: No play restrictions
        if (empty($items)) {
            Alert::add('No restricted cards on FoWDB at the moment', 'warning');
            Redirect::to('/');
        }

        return (new Page)
            ->template('pages/public/banlist/index')
            ->title('Banned and Limited Cards')
            ->variables([
                'items' => $items
            ])
            ->options([
                'scripts' => ['public/play-restrictions/play-restrictions']
            ])
            ->render();
    }
}
