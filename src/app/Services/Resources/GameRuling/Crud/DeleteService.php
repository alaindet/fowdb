<?php

namespace App\Services\Resources\GameRuling\Crud;

use App\Base\CrudService;
use App\Base\CrudServiceInterface;
use App\Models\Card;
use App\Models\GameRuling as Model;

class DeleteService extends CrudService
{
    public $model = Model::class;

    public function syncDatabase(): CrudServiceInterface
    {
        $statement = fd_statement('delete')
            ->table('game_rulings')
            ->where('id = :id');

        $bind = [':id' => $this->old['id']];

        fd_database()
            ->delete($statement)
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
        $card = (new Card)->byId($this->old['cards_id'], ['name', 'code']);

        $label = "{$card['name']} ({$card['code']})";
        $link = '<a href="'.fd_url('rulings/manage').'">Rulings</a>';
        $id = $this->old['id'];

        $message = (
            "Ruling #{$id} for card <strong>{$label}</strong> deleted. ".
            "Go back to the <strong>{$link}</strong> page."
        );

        $uri = fd_url('card/'.urlencode($card['code']));

        return [$message, $uri];
    }
}
