<?php

namespace App\Http\Controllers\Admin;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Http\Response\Redirect;
use App\Services\Alert;
use App\Views\Page;
use App\Models\GameSet;
use App\Models\Card;

class ArtistsController extends Controller
{
    public function selectSetForm(): string
    {
        return (new Page)
            ->template('pages/admin/artists/select-set')
            ->title('Artists,Select set')
            ->options([
                'scripts' => [
                    'admin/artists/select-set'
                ]
            ])
            ->render();
    }

    public function selectCardForm(Request $request, string $setId): string
    {
        // Fetch the set
        $set = (new GameSet)->byId($setId, ['id', 'name', 'code']);

        // Fetch cards for this set
        $cards = fd_database()
            ->select(
                statement('select')
                ->select(['id','image_path','artist_name'])
                ->from('cards')
                ->where('sets_id = :setid')
                ->orderBy('num')
            )
            ->bind([':setid' => $set['id']])
            ->get();

        return (new Page)
            ->template('pages/admin/artists/select-card')
            ->title('Artists,Select card')
            ->variables([
                'cards' => $cards,
                'set' => $set
            ])
            ->render();
    }

    public function cardForm(Request $request, string $cardId): string
    {
        // Fetch the set
        $model = new Card;
        $card = $model->byId($cardId);

        // Next card
        $nextCard = $model->getNext($card['sorted_id'], ['id']);

        return (new Page)
            ->template('pages/admin/artists/show-card')
            ->title('Artists,Card')
            ->variables([
                'card' => $card,
                'next_card' => $nextCard
            ])
            ->options([
                'dependencies' => [
                    'lightbox' => true,
                    'jqueryui' => true
                ],
                'scripts' => [
                    'admin/artists/show-card'
                ]
            ])
            ->render();
    }

    public function store(Request $request): string
    {
        // Validate input
        $request->validate([
            'card-id' => ['required','is:integer','exists:cards,id'],
            'artist-name' => ['required'],
        ]);

        // Fetch card data
        $model = new Card();
        $card = $model->byId($request->input()->post('card-id'));

        // Update card data
        fd_config()
            ->update(
                statement('update')
                    ->table('cards')
                    ->values([ 'artist_name' => ':artist' ])
                    ->where('id = :id')
            )
            ->bind([
                ':id' => $card['id'],
                ':artist' => $request->input()->post('artist-name')
            ])
            ->execute();

        // Fetch next card
        $nextCard = $model->getNext($card['sorted_id'], ['id']);

        // ERROR: No next card exists
        if (empty($nextCard)) {
            Alert::add('No next card exists.');
            Redirect::to("artists/set/{$card['sets_id']}");
        }

        // Go to the next card
        Redirect::to("artists/card/{$nextCard['id']}");
    }
}
