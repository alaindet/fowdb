<?php

namespace App\Models;

use App\Base\Model;

class GameRuling extends Model
{
    public $table = 'game_rulings';

    /**
     * Returns all rulings having the same card ID
     *
     * @param string $cardId
     * @param array $fields
     * @param array $fieldsToRender
     * @return array
     */
    public function byCardId(
        string $cardId,
        array $fields = null,
        array $fieldsToRender = []
    ): array
    {
        $resources = fd_database()
            ->select(
                fd_statement('select')
                    ->select(isset($fields) ? implode(',', $fields) : '*')
                    ->from($this->table)
                    ->where('cards_id = :cardid')
                    ->orderBy(['date DESC'])
            )
            ->bind([':cardid' => $cardId])
            ->get();

        // Return raw data (default)
        if (empty($fieldsToRender)) return $resources;

        // Render fields
        foreach ($resources as &$resource) {
            foreach ($fieldsToRender as $field) {
                $resource[$field] = render($resource[$field]);
            }
        }

        return $resources;
    }
}
