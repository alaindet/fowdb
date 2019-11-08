<?php

namespace App\Services\Resources\PlayRestriction;

use App\Base\CrudService;
use App\Base\CrudServiceInterface;
use App\Services\Resources\PlayRestriction\PlayRestrictionInputProcessor;
use App\Models\Card;
use App\Models\PlayRestriction as Model;

class PlayRestrictionUpdateService extends CrudService
{
    protected $inputProcessor = PlayRestrictionInputProcessor::class;
    protected $model = Model::class;

    public function syncDatabase(): CrudServiceInterface
    {
        $placeholders = [];
        $bind = [':id' => $this->old['id']];

        foreach (array_keys($this->new) as $key) {
            // Avoid extra props (with keys like _wassup)
            if (substr($key, 0, 1) !== '_') {
                $placeholder = ":{$key}";
                $placeholders[$key] = $placeholder;
                $bind[$placeholder] = $this->new[$key];
            }
        }

        database()
            ->update(
                statement('update')
                    ->table('play_restrictions')
                    ->values($placeholders)
                    ->where('id = :id')
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
            'Restriction for card <strong>'.
                "{$card['name']} ({$card['code']})".
            '</strong> updated. Go back to the <strong>'.
                '<a href="'.url('restrictions/manage').'">Restrictions</a>'.
            '</strong> page.'
        );

        $uri = url('card/'.urlencode($card['code']));

        return [$message, $uri];
    }
}
