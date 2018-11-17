<?php

namespace App\Models;

class Ruling
{
    public $table = 'rulings';

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
        $resources = database()
            ->select(
                statement('select')
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
