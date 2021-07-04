<?php

namespace App\Http\Controllers\Admin;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Http\Response\Redirect;
use App\Services\Alert;
use App\Services\Resources\PlayRestriction\PlayRestrictionCreateService;
use App\Services\Resources\PlayRestriction\PlayRestrictionDeleteService;
use App\Services\Resources\PlayRestriction\PlayRestrictionUpdateService;
use App\Views\Page;
use App\Models\PlayRestriction as Model;
use App\Views\PlayRestriction as View;
use App\Services\Database\Statement\SqlStatement;
use App\Models\Card as CardModel;

class PlayRestrictionsController extends Controller
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
            'format' => 'formats_id',
            'deck' => 'deck',
            'copies' => 'copies'
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
                'r.id id',
                'r.deck deck',
                'r.copies copies',
                'r.formats_id format_id',
                'f.name format_name',
                'c.id card_id',
                'c.name card_name',
                'c.code card_code',
                'c.thumb_path card_image'
            ])
            ->from(
                'play_restrictions r
                INNER JOIN cards c ON r.cards_id = c.id
                INNER JOIN game_formats f ON r.formats_id = f.id'
            )
            ->where('c.narp = 0') // Base prints only
            ->orderBy('r.id DESC');
        
        $bind = [];

        $filters = $this->setFilters($request, $statement, $bind);

        $database = database()
            ->select($statement)
            ->bind($bind)
            ->page($request->input()->get('page') ?? 1)
            ->paginationLink($request->getCurrentUrl());

        $items = $database->paginate();

        return (new Page)
            ->template('pages/admin/restrictions/index')
            ->title('Play Restrictions,Manage')
            ->variables([
                // paginate() must be called before paginationInfo()
                'items' => $items,
                'pagination' => $database->paginationInfo(),
                'filters' => $filters
            ])
            ->render();
    }

    public function createForm(Request $request): string
    {
        // User passed a card id (Ex.: from card page)
        if ($request->input()->get('card') !== null) {
            $card = (new CardModel)->byId(
                $request->input()->get('card'),
                ['id', 'name', 'code', 'image_path as image']
            );
        }

        // Render the page
        return (new Page)
            ->template('pages/admin/restrictions/create')
            ->title('Play Restrictions,Create')
            ->variables([
                'previous' => $request->input()->previous(),
                'card' => $card ?? null
            ])
            ->options([
                'dependencies' => [
                    'lightbox' => true,
                    'jqueryui' => true,
                ],
                'scripts' => [
                    // Same autocomplete and validation needs as rulings!
                    'admin/rulings-create'
                ]
            ])
            ->render();
    }

    public function create(Request $request): string
    {
        $request->validate('post', [
            'card-id' => ['required','is:integer','exists:cards,id'],
            'format-id' => ['required','is:integer','exists:game_formats,id'],
            'deck' => ['required','is:integer','enum:0,1,2,3'],
            'copies' => ['required','is:integer'],
        ]);

        $service = new PlayRestrictionCreateService($request->input()->post());
        $service->processInput();
        $service->syncDatabase();
        [$message, $uri] = $service->getFeedback();

        Alert::add($message, 'info');
        Redirect::toAbsoluteUrl($uri);
    }

    public function updateForm(Request $request, string $id): string
    {
        $statement = statement('select')
            ->select([
                'r.id id',
                'r.deck deck',
                'r.copies copies',
                'r.formats_id format_id',
                'f.name format_name',
                'c.id card_id',
                'c.name card_name',
                'c.code card_code',
                'c.thumb_path card_image'
            ])
            ->from(
                'play_restrictions r
                INNER JOIN cards c ON r.cards_id = c.id
                INNER JOIN game_formats f ON r.formats_id = f.id'
            )
            ->where('r.id = :id');

        $item = database()
            ->select($statement)
            ->bind([':id' => $id])
            ->first();

        $card = [
            'id' => $item['card_id'],
            'name' => $item['card_name'],
            'code' => $item['card_code'],
            'image' => $item['card_image'],
        ];

        // Render the page
        return (new Page)
            ->template('pages/admin/restrictions/update')
            ->title('Play Restrictions,Update')
            ->variables([
                'previous' => $request->input()->previous(),
                'item' => $item,
                'card' => $card
            ])
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
            'card-id' => ['required','is:integer','exists:cards,id'],
            'format-id' => ['required','is:integer','exists:game_formats,id'],
            'deck' => ['required','is:integer','enum:0,1,2,3'],
            'copies' => ['required','is:integer'],
        ]);

        $service = new PlayRestrictionUpdateService(
            $request->input()->post(),
            $id
        );
        $service->processInput();
        $service->syncDatabase();
        [$message, $uri] = $service->getFeedback();
        
        Alert::add($message, 'info');
        Redirect::toAbsoluteUrl($uri);
    }

    public function deleteForm(Request $request, string $id): string
    {
        $statement = statement('select')
            ->select([
                'r.id id',
                'r.deck deck',
                'r.copies copies',
                'r.formats_id format_id',
                'f.name format_name',
                'c.id card_id',
                'c.name card_name',
                'c.code card_code',
                'c.thumb_path card_image'
            ])
            ->from(
                'play_restrictions r
                INNER JOIN cards c ON r.cards_id = c.id
                INNER JOIN game_formats f ON r.formats_id = f.id'
            )
            ->where('r.id = :id');

        $item = database()
            ->select($statement)
            ->bind([':id' => $id])
            ->first();

        $card = [
            'id' => $item['card_id'],
            'name' => $item['card_name'],
            'code' => $item['card_code'],
            'image' => $item['card_image'],
        ];

        // Render the page
        return (new Page)
            ->template('pages/admin/restrictions/delete')
            ->title('Play Restrictions,Delete')
            ->variables([
                'item' => $item
            ])
            ->options([
                'dependencies' => [
                    'lightbox' => true,
                ],
            ])
            ->render();
    }

    public function delete(Request $request, string $id): string
    {
        $service = new PlayRestrictionDeleteService(null, $id);
        $service->syncDatabase();
        [$message, $uri] = $service->getFeedback();
        
        Alert::add($message, 'info');
        Redirect::toAbsoluteUrl($uri);
    }
}
