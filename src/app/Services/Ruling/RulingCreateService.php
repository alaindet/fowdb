<?php

namespace App\Services\Ruling;

use App\Base\CrudService;
use App\Base\CrudServiceInterface;
use App\Services\Ruling\RulingInputProcessor;
use App\Models\Card;
use App\Models\Ruling;

class RulingCreateService extends CrudService
{
    public $inputProcessor = RulingInputProcessor::class;

    public function syncDatabase(): CrudServiceInterface
    {
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
        $card = Card::getById($cardId, ['name', 'code']);

        // Build the success message
        $label = "{$card['name']} ({$card['code']})";
        $link = '<a href="'.url('rulings/manage').'">Rulings</a>';

        $message = collapse(
            "New ruling for card <strong>{$label}</strong> added. ",
            "Go back to the <strong>{$link}</strong> page."
        );

        $uri = url_old('card', ['code' => urlencode($card['code'])]);

        return [$message, $uri];
    }
}
