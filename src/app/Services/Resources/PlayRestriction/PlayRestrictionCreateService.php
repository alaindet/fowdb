<?php

namespace App\Services\Resources\PlayRestriction;

use App\Base\CrudService;
use App\Base\CrudServiceInterface;
use App\Services\Resources\PlayRestriction\PlayRestrictionInputProcessor;
use App\Models\Card;
use App\Models\PlayRestriction as Model;

class PlayRestrictionCreateService extends CrudService
{
    public $inputProcessor = PlayRestrictionInputProcessor::class;

    public function syncDatabase(): CrudServiceInterface
    {
        $placeholders = [];
        $bind = [];
        foreach (array_keys($this->new) as $key) {
            if (substr($key, 0, 1) !== '_') {
                $placeholder = ":{$key}";
                $placeholders[$key] = $placeholder;
                $bind[$placeholder] = $this->new[$key];
            }
        }

        // Create a new card entity on the database
        database()
            ->insert(
                statement('insert')
                    ->table('play_restrictions')
                    ->values($placeholders)
            )
            ->bind($bind)
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
        $cardId = $this->inputProcessorInstance->getInput('card-id');
        $card = (new Card)->byId($cardId, ['name', 'code']);

        // Build the success message
        $message = (
            'New restriction for card <strong>'.
                "{$card['name']} ({$card['code']})".
            '</strong> added. Go back to the <strong>'.
                '<a href="'.url('restrictions/manage').'">Restrictions</a>'.
            '</strong> page.'
        );

        $uri = url('card/'.urlencode($card['code']));

        return [$message, $uri];
    }
}
