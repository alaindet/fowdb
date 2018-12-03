<?php

namespace App\Services\Resources\PlayRestriction;

use App\Base\CrudService;
use App\Base\CrudServiceInterface;
use App\Services\Resources\PlayRestriction\PlayRestrictionInputProcessor;
use App\Models\Card;
use App\Models\PlayRestriction as Model;

class PlayRestrictionDeleteService extends CrudService
{
    protected $model = Model::class;

    public function syncDatabase(): CrudServiceInterface
    {
        database()
            ->delete(
                statement('delete')
                    ->table('play_restrictions')
                    ->where('id = :id')
            )
            ->bind([':id' => $this->old['id']])
            ->execute();

        return $this;
    }

    /**
     * Returns the success message and the redirect URI
     *
     * @return string
     */
    public function getFeedback(): array
    {
        // Read the card's data
        $card = (new Card)->byId($this->old['cards_id'], ['name', 'code']);

        // Build the success message
        $message = collapse(
            'Restriction for card <strong>',
                "{$card['name']} ({$card['code']})",
            '</strong> deleted. Go back to the <strong>',
                '<a href="',url('restrictions/manage'),'">Restrictions</a>',
            '</strong> page.'
        );

        $uri = url_old('card', ['code' => urlencode($card['code'])]);

        return [$message, $uri];
    }
}
