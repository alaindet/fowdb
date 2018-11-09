<?php

namespace App\Services\Ruling;

use App\Base\CrudService;
use App\Base\CrudServiceInterface;
use App\Services\Ruling\RulingInputProcessor;
use App\Models\Card;
use App\Models\Ruling;

class RulingDeleteService extends CrudService
{
    public $model = Ruling::class;

    public function syncDatabase(): CrudServiceInterface
    {
        $statement = statement('delete')
            ->table('rulings')
            ->where('id = :id');

        $bind = [':id' => $this->old['id']];

        database()
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
        $card = Card::getById($this->old['cards_id'], ['name', 'code']);

        $label = "{$card['name']} ({$card['code']})";
        $link = '<a href="'.url('rulings/manage').'">Rulings</a>';
        $id = $this->old['id'];

        $message = collapse(
            "Ruling #{$id} for card <strong>{$label}</strong> deleted. ",
            "Go back to the <strong>{$link}</strong> page."
        );

        $uri = url_old('card', ['code' => urlencode($card['code'])]);

        return [$message, $uri];
    }
}
