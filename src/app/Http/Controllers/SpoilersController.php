<?php

namespace App\Http\Controllers;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Views\Page;
use App\Http\Response\Redirect;
use App\Services\Alert;

class SpoilersController extends Controller
{
    private function getSpoilerSets(): array
    {
        $items = [];
        $map = lookup('sets.code2id');

        foreach (lookup('spoilers.sets') as $spoiler) {

            $statement = statement('select')
                ->select([
                    'id',
                    'back_side',
                    'code',
                    'num',
                    'name',
                    'type',
                    'image_path',
                    'thumb_path'
                ])
                ->from('cards')
                ->where('sets_id = :setid')
                ->orderBy('id DESC');

            $cards = database()
                ->select($statement)
                ->bind([':setid' => $spoiler['id']])
                ->get();

            // Count just base faces
            $counter = 0;
            if (!empty($cards)) {
                foreach ($cards as $card) {
                    if ($card['back_side'] === '0') $counter++;
                }
            }

            // Add 'spoiled' and 'cards' elements to set
            $spoiler['spoiled'] = $counter;
            $spoiler['cards'] = $cards;

            // Add this set to existing sets
            $items[] = $spoiler;
        }

        return $items;
    }

    public function index(Request $request): string
    {
        // ERROR: Missing spoilers at the moment
        if (empty(lookup('spoilers.ids'))) {
            Alert::add('No spoilers on FoWDB at the moment, sorry.', 'warning');
            Redirect::to('/');
        }

        return (new Page)
            ->template('pages/public/cards/spoiler/index')
            ->title('Spoiler')
            ->variables([ 'items' => $this->getSpoilerSets() ])
            ->options([
                'scripts' => [
                    'public/search',
                    'public/spoiler'
                ],
            ])
            ->render();
    }
}
