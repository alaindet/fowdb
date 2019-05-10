<?php

namespace App\Services\Resources\GameRuling\Crud;

use App\Base\CrudService;
use App\Base\CrudServiceInterface;
use App\Services\Resources\GameRuling\Crud\InputProcessor;
use App\Models\Card;

class CreateService extends CrudService
{
    public $inputProcessor = InputProcessor::class;

    public function syncDatabase(): CrudServiceInterface
    {
        // Create ruling entity on the database
        fd_database()
            ->insert(statement('insert')
                ->table('game_rulings')
                ->values([
                    'cards_id' => ':cardid',
                    'date' => ':date',
                    'is_errata' => ':errata',
                    'text' => ':text'
                ])
            )
            ->bind([
                ':cardid' => $this->new['cards_id'],
                ':date' => $this->new['date'],
                ':errata' => $this->new['is_errata'],
                ':text' => $this->new['text']
            ])
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
        $label = "{$card['name']} ({$card['code']})";
        $link = '<a href="'.url('rulings/manage').'">Rulings</a>';

        $message = (
            "New ruling for card <strong>{$label}</strong> added. ".
            "Go back to the <strong>{$link}</strong> page."
        );

        $uri = url('card/'.urlencode($card['code']));

        return [$message, $uri];
    }
}
