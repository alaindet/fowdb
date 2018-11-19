<?php

namespace App\Http\Controllers;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Http\Response\Redirect;
use App\Services\Alert;
use App\Services\Ruling\RulingCreateService;
use App\Services\Ruling\RulingDeleteService;
use App\Services\Ruling\RulingUpdateService;
use App\Views\Page;

class RulingsController extends Controller
{
    public function indexManage(Request $request): string
    {
        // Get data from database
        $database = database()
            ->select(
                statement('select')
                    ->select([
                        'c.code as card_code',
                        'c.name as card_name',
                        'r.id as ruling_id',
                        'r.is_errata as ruling_is_errata',
                        'r.date as ruling_date',
                        'r.text as ruling_text'
                    ])
                    ->from(
                        'rulings r INNER JOIN cards c ON r.cards_id = c.id'
                    )
                    ->orderBy([
                        'r.date DESC',
                        'r.id DESC'
                    ])
            )
            ->page($request->input()->get('page') ?? 1)
            ->paginationLink($request->getCurrentUrl());

        // Render the page
        return (new Page)
            ->template('pages/admin/rulings/index')
            ->title('Rulings,Manage')
            ->variables([
                // paginate() must be called before paginationInfo()!
                'items' => $database->paginate(),
                'pagination' => $database->paginationInfo(),
            ])
            ->render();
    }

    public function createForm(Request $request): string
    {
        $cardId = $request->input()->get('card-id');

        // User passed a card id (Ex.: from card page)
        if (isset($cardId)) {
            $statement = statement('select')
                ->select(['id', 'name', 'code', 'image_path'])
                ->from('cards')
                ->where('id = :id')
                ->limit(1);

            $card = database()
                ->select($statement)
                ->bind([':id' => $cardId])
                ->first();
        }

        // Render the page
        return (new Page)
            ->template('pages/admin/rulings/create')
            ->title('Rulings,Create')
            ->variables([
                'card' => $card ?? null,
            ])
            ->options([
                'dependencies' => [
                    'lightbox' => true,
                    'jqueryui' => true,
                ],
                'scripts' => [
                    'admin/rulings-create'
                ]
            ])
            ->render();
    }

    public function create(Request $request): string
    {
        $request->validate('post', [
            'card-id' => ['required:1','is:integer','exists:cards,id'],
            'ruling-errata' => ['required:0','is:integer','enum:0,1'],
            'ruling-date' => ['required:0','is:date'],
            'ruling-text' => ['required:1'],
        ]);

        $service = new RulingCreateService($request->input()->post());        $service->processInput();
        $service->syncDatabase();
        [$message, $uri] = $service->getFeedback();

        Alert::add($message, 'info');
        Redirect::toAbsoluteUrl($uri);
    }

    public function updateForm(Request $request, string $id): string
    {
        $item = database()
            ->select(
                statement('select')
                    ->select([
                        'c.id card_id',
                        'c.name card_name',
                        'c.code card_code',
                        'c.image_path card_image',
                        'r.id as ruling_id',
                        'r.date ruling_date',
                        'r.is_errata ruling_is_errata',
                        'r.text ruling_text',
                    ])
                    ->from('rulings r INNER JOIN cards c ON r.cards_id = c.id')
                    ->where('r.id = :id')
                    ->limit(1)
            )
            ->bind([':id' => $id])
            ->first();

        $item['card_image'] = asset($item['card_image']);
        $item['card_label'] = "{$item['card_name']} ({$item['card_code']})";

        // Render the page
        return (new Page)
            ->template('pages/admin/rulings/update')
            ->title('Rulings,Update')
            ->variables($item)
            ->options([
                'dependencies' => [
                    'lightbox' => true,
                ],
            ])
            ->render();
    }

    public function update(Request $request, string $id): string
    {
        $request->validate('post', [
            'ruling-errata' => ['required:0','is:integer','enum:0,1'],
            'ruling-date' => ['required:1','is:date'],
            'ruling-text' => ['required:1'],
        ]);

        $service = new RulingUpdateService($request->input()->post());
        $service->setOldResource($id);
        $service->processInput();
        $service->syncDatabase();
        [$message, $uri] = $service->getFeedback();
        
        Alert::add($message, 'info');
        Redirect::toAbsoluteUrl($uri);
    }

    public function deleteForm(Request $request, string $id): string
    {
        $item = database()
            ->select(
                statement('select')
                    ->select([
                        'c.id card_id',
                        'c.name card_name',
                        'c.code card_code',
                        'c.image_path card_image',
                        'r.id as ruling_id',
                        'r.date ruling_date',
                        'r.is_errata ruling_is_errata',
                        'r.text ruling_text',
                    ])
                    ->from('rulings r INNER JOIN cards c ON r.cards_id = c.id')
                    ->where('r.id = :id')
                    ->limit(1)
            )
            ->bind([':id' => $id])
            ->first();

        $item['card_image'] = asset($item['card_image']);
        $item['card_label'] = "{$item['card_name']} ({$item['card_code']})";
        $item['ruling_text'] = render($item['ruling_text']);

        // Render the page
        return (new Page)
            ->template('pages/admin/rulings/delete')
            ->title('Rulings,Delete')
            ->variables($item)
            ->options([
                'dependencies' => [
                    'lightbox' => true,
                ],
            ])
            ->render();
    }

    public function delete(Request $request, string $id): string
    {
        $service = new RulingDeleteService();
        $service->setOldResource($id);
        $service->syncDatabase();
        [$message, $uri] = $service->getFeedback();
        
        Alert::add($message, 'info');
        Redirect::toAbsoluteUrl($uri);
    }
}
