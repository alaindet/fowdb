<?php

namespace App\Http\Controllers\Admin;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Http\Response\Redirect;
use App\Services\Alert;
use App\Services\Resources\GameRuling\Crud\CreateService;
use App\Services\Resources\GameRuling\Crud\DeleteService;
use App\Services\Resources\GameRuling\Crud\UpdateService;
use App\Views\Page;
use App\Services\Database\Statement\SqlStatement;

class GameRulingsController extends Controller
{
    /**
     * Acquires specific GET parameters,
     * alters (filters) the query if needed,
     * returns the list of valid GET parameters names
     *
     * @param Request $request
     * @param SqlStatement $statement
     * @param array $bind
     * @return array
     */
    private function setFilters(
        Request &$request,
        SqlStatement &$statement,
        array &$bind
    ): array
    {
        // GET param => DB field
        $paramToColumn = [
            'card' => 'cards_id',
            // Add filters here...
        ];

        // Read only valid GET filters
        $params = $request->input()->getMultiple(array_keys($paramToColumn));

        // No valid filters passed
        if (empty($params)) return [];

        // Process each filter (by altering the query)
        foreach ($params as $param => $value) {
            $column = $paramToColumn[$param];
            $placeholder = ":{$column}";
            // Ex.: card = :card
            $statement->where("{$column} = {$placeholder}");
            $bind[$placeholder] = $value;
        }

        // One ore more filters were passed
        return $params;
    }

    public function index(Request $request): string
    {
        $statement = statement('select')
            ->select([
                'c.id as card_id',
                'c.code as card_code',
                'c.name as card_name',
                'r.id as ruling_id',
                'r.is_errata as ruling_is_errata',
                'r.date as ruling_date',
                'r.text as ruling_text'
            ])
            ->from(
                'game_rulings r
                INNER JOIN cards c ON r.cards_id = c.id'
            )
            ->orderBy([
                'r.date DESC',
                'r.id DESC'
            ]);

        $bind = [];

        $filters = $this->setFilters($request, $statement, $bind);

        // Get data from database
        $database = database()
            ->select($statement)
            ->bind($bind)
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
                'filters' => $filters
            ])
            ->render();
    }

    public function createForm(Request $request): string
    {
        $cardId = $request->input()->get('card');

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
            'card-id' => ['required','is:integer','exists:cards,id'],
            'ruling-errata' => ['required:0','is:integer','enum:0,1'],
            'ruling-date' => ['required:0','is:date'],
            'ruling-text' => ['required'],
        ]);

        $service = new CreateService($request->input()->post());
        $service->processInput();
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
                    ->from(
                        'game_rulings r
                        INNER JOIN cards c ON r.cards_id = c.id'
                    )
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
            'ruling-errata' => ['required:0','is:boolean'],
            'ruling-date' => ['required','is:date'],
            'ruling-text' => ['required'],
        ]);

        $service = new UpdateService($request->input()->post(), $id);
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
                    ->from(
                        'game_rulings r
                        INNER JOIN cards c ON r.cards_id = c.id'
                    )
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
        $service = new DeleteService(null, $id);
        $service->syncDatabase();
        [$message, $uri] = $service->getFeedback();
        
        Alert::add($message, 'info');
        Redirect::toAbsoluteUrl($uri);
    }
}
