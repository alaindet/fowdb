<?php

namespace App\Http\Controllers;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Views\Page\Page;

class ErrataController extends Controller
{
    public function index(Request $request): string
    {
        $statement = fd_statement('select')
            ->select([
                'c.name card_name',
                'c.code card_code',
                'r.date ruling_date',
                'r.text ruling_text',
            ])
            ->from(
                'game_rulings r
                INNER JOIN cards c ON r.cards_id = c.id'
            )
            ->where('r.is_errata = 1')
            ->orderBy('r.date DESC');

        $database = fd_database()
            ->select($statement)
            ->page($request->input()->get('page') ?? 1)
            ->paginationLink($request->getCurrentUrl());

        return (new Page)
            ->template('pages/public/errata/index')
            ->title('Errata')
            ->variables([
                // paginate() must be called before paginationInfo()!
                'items' => $database->paginate(),
                'pagination' => $database->paginationInfo()
            ])
            ->render();
    }
}
