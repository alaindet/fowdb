<?php

namespace App\Http\Controllers\Admin;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Views\Page;
use App\Services\Card\CardCreateService;
use App\Services\Card\CardDeleteService;
use App\Services\Card\CardUpdateService;
use App\Services\Session;
use App\Http\Request\Input;

/**
 * Contains actions for JUDGE routes only
 * Pubilc actions on cards are provided by ...\CardsController
 */
class CardsController extends Controller
{
    public function indexManage(): string
    {
        return (new Page)
            ->template('pages/admin/cards/index')
            ->title('Admin ~ Cards ~ Index')
            ->render();
    }

    public function createForm(): string
    {
        return (new Page)
            ->template('pages/admin/cards/create')
            ->title('Cards,Create')
            ->variables([
                'previous' => Session::get(Input::PREVIOUS_INPUT) ?? null
            ])
            ->render();
    }

    public function create(Request $request): string
    {
        $input = array_merge(
            $request->input()->post(),
            $request->input()->files()
        );

        $request->validate('post', [
            
            // Required fields
            'image' => ['required','is:file'],
            'narp' => ['required','is:integer','enum:0,1,2,3'],
            'set' => ['required','except:0'],
            'number' => ['required','is:integer'],
            'back-side' => ['required','is:integer','enum:0,1,2,3,4'],
            'name' => ['required','except:'],

            // Optional fields
            'code' => ['required:0',],
            'rarity' => ['required:0','enum:0,c,u,r,sr,s,ar'],
            'attribute' => ['required:0','is:array','enum:w,r,u,g,b,v'],
            'type' => ['required:0'],
            'attribute-cost' => ['required:0'],
            'free-cost' => ['required:0'],
            'divinity-cost' => ['required:0','is:integer'],
            'atk' => ['required:0','is:integer'],
            'def' => ['required:0','is:integer'],
            'race' => ['required:0'],
            'text' => ['required:0'],
            'flavor-text' => ['required:0'],
            'artist-name' => ['required:0'],

        ], $input);

        $service = new CardCreateService($input);
        $service->processInput();
        $service->syncDatabase();
        $service->syncFilesystem();

        return log_html($service->debug());

        // [$message, $uri] = $service->getFeedback();

        // Alert::add($message, 'info');
        // Redirect::toAbsoluteUrl($uri);
    }

    public function updateForm(): string
    {
        return __METHOD__;
    }

    public function update(): string
    {
        return __METHOD__;
    }

    public function deleteForm(): string
    {
        return __METHOD__;
    }

    public function delete(): string
    {
        return __METHOD__;
    }
}
