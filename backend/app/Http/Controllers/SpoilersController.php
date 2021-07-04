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
        $map = array_flip(lookup('layouts.id2name'));
        $basicLayout = '0';
        $alternativeLayout = (string) $map['Alternative'];

        foreach (lookup('spoilers.sets') as $spoiler) {

            $statement = statement('select')
                ->select([
                    'id',
                    'layout',
                    'code',
                    'num',
                    'name',
                    'type_bit',
                    'image_path',
                    'thumb_path'
                ])
                ->from('cards')
                ->where('sets_id = :setid')
                ->orderBy('id DESC');

            $cardsRaw = database()
                ->select($statement)
                ->bind([':setid' => $spoiler['id']])
                ->get();

            // Remove alternative duplicates
            $faces = [];
            $counter = 0;
            $alternatives = [];
            foreach ($cardsRaw as $card) {
                if (
                    $card['layout'] !== $alternativeLayout || (
                        $card['layout'] === $alternativeLayout &&
                        !isset($alternatives[$card['code']])
                    )
                ) {
                    $alternatives[$card['code']] = true;
                    $counter++;
                    $faces[] = $card;
                }
            }

            // Add 'spoiled' and 'cards' elements to set
            $spoiler['spoiled'] = $counter;
            $spoiler['cards'] = $faces;

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
