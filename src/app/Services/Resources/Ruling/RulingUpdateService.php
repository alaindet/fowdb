<?php

namespace App\Services\Resources\Ruling;

use App\Base\CrudService;
use App\Base\CrudServiceInterface;
use App\Services\Resources\Ruling\RulingInputProcessor as InputProcessor;
use App\Models\Card;
use App\Models\GameRuling as Model;

class RulingUpdateService extends CrudService
{
    public $inputProcessor = InputProcessor::class;
    public $model = Model::class;

    public function syncDatabase(): CrudServiceInterface
    {
        // Create ruling entity on the database
        database()
            ->update(statement('update')
                ->table('game_rulings')
                ->values([
                    'date' => ':date',
                    'is_errata' => ':errata',
                    'text' => ':text'
                ])
                ->where('id = :id')
            )
            ->bind([
                ':id' => $this->old['id'],
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
        $card = (new Card)->byId($this->old['cards_id'], ['name', 'code']);

        // Build the success message
        $label = "{$card['name']} ({$card['code']})";
        $link = '<a href="'.url('rulings/manage').'">Rulings</a>';
        $id = $this->old['id'];

        $message = collapse(
            "Ruling #{$id} for card <strong>{$label}</strong> updated. ",
            "Go back to the <strong>{$link}</strong> page."
        );

        $uri = url_old('card', ['code' => urlencode($card['code'])]);

        return [$message, $uri];
    }
}
