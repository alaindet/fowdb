<?php

namespace App\Http\Controllers;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Views\Page;
use App\Models\Card;
use App\Models\Ruling;

class RulingsController extends Controller
{
    public function indexManage(Request $request): string
    {
        // Assemble current absolute URL
        $url = url($request->path());
        $queryString = $request->queryString();
        if (!empty($queryString)) $url .= '?'.$queryString;

        $statement = statement('select')
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
            ]);

        // Get data from database
        $database = database()
            ->select($statement)
            ->perPage(25)
            ->page($request->input()->get('page') ?? 1)
            ->paginationLink($url);

        $rulings = $database->paginate();

        // Render the page
        return (new Page)
            ->template('pages/admin/rulings/index')
            ->title('Admin ~ Rulings ~ Index')
            ->variables([
                'pagination' => $database->paginationInfo(),
                'rulings' => $rulings
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
            ->title('Admin ~ Rulings ~ Create')
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
        // Validate inputs
        $request->validate('post', [
            'card-id' => ['required:1','is:integer','exists:cards,id'],
            'ruling-errata' => ['required:0','is:integer','enum:0,1'],
            'ruling-date' => ['required:0','is:date'],
            'ruling-text' => ['required:1'],
        ]);

        $input = $request->input();

        // Read the card's data
        $card = Card::getById($input->post('card-id'), ['id', 'name', 'code']);

        // Create ruling entity on the database
        database()
            ->insert(statement('insert')
                ->table('rulings')
                ->values([
                    'cards_id' => ':cardid',
                    'date' => ':date',
                    'is_errata' => ':errata',
                    'text' => ':text'
                ])
            )
            ->bind([
                ':cardid' => $card['id'],
                ':date' => $input->post('ruling-date') ?? date('Y-m-d'),
                ':errata' => $input->post('ruling-errata') ?? '0',
                ':text' => $input->post('ruling-text')
            ])
            ->execute();

        // Build the success message
        $label = "{$card['name']} ({$card['code']})";
        $link = '<a href="'.url('rulings/manage').'">Rulings</a>';
        alert(
            collapse(
                "New ruling for card <strong>{$label}</strong> added. ",
                "Go back to the <strong>{$link}</strong> page."
            ),
            'info'
        );

        // Redirect to the card's page
        redirect_old('card', ['code' => urlencode($card['code'])]);
    }

    public function updateForm(Request $request, $id): string
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
            ->title('Admin ~ Rulings ~ Update')
            ->variables($item)
            ->options([
                'dependencies' => [
                    'lightbox' => true,
                ],
            ])
            ->render();
    }

    public function update(Request $request, $id): string
    {
        // Validate inputs
        $request->validate('post', [
            'ruling-errata' => ['required:0','is:integer','enum:0,1'],
            'ruling-date' => ['required:1','is:date'],
            'ruling-text' => ['required:1'],
        ]);

        // Read the card's data
        $card = Card::getById($cardId, ['id', 'name', 'code']);

        // Alias the input instance
        $input = $request->input();

        // Update ruling entity on the database
        database()
            ->update(statement('update')
                ->table('rulings')
                ->set([
                    'date' => ':date',
                    'is_errata' => ':errata',
                    'text' => ':text'
                ])
                ->where('id = :id')
            )
            ->bind([
                ':id' => $id,
                ':date' => $input->post('ruling-date') ?? date('Y-m-d'),
                ':errata' => $input->post('ruling-errata') ?? '0',
                ':text' => $input->post('ruling-text')
            ])
            ->execute();

        // Build the success message
        $label = "{$card['name']} ({$card['code']})";
        $link = '<a href="'.url('rulings/manage').'">Rulings</a>';
        alert(
            collapse(
                "Ruling #{$id} for card <strong>{$label}</strong> updated. ",
                "Go back to the <strong>{$link}</strong> page."
            ),
            'info'
        );

        // Redirect to the card's page
        redirect_old('card', ['code' => urlencode($card['code'])]);
    }

    public function deleteForm(Request $request, $id): string
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
            ->template('pages/admin/rulings/delete')
            ->title('Admin ~ Rulings ~ Delete')
            ->variables($item)
            ->options([
                'dependencies' => [
                    'lightbox' => true,
                ],
            ])
            // TEST
            ->minify(false)
            ->render();
    }

    public function delete(Request $request, $id): string
    {
        $old = Ruling::getById($id, ['cards_id']);
        $card = Card::getById($old['cards_id'], ['name', 'code']);

        // Update ruling entity on the database
        database()
            ->delete(statement('delete')
                ->table('rulings')
                ->where('id = :id')
            )
            ->bind([':id' => $id])
            ->execute();

        // Build the success message
        $label = "{$card['name']} ({$card['code']})";
        $link = '<a href="'.url('rulings/manage').'">Rulings</a>';
        alert(
            collapse(
                "Ruling #{$id} for card <strong>{$label}</strong> deleted. ",
                "Go back to the <strong>{$link}</strong> page."
            ),
            'info'
        );

        // Redirect to the card's page
        redirect_old('card', ['code' => urlencode($card['code'])]);
    }
}
