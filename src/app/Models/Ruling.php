<?php

namespace App\Models;

class Ruling
{
    public static $table = 'rulings';

    public static function getByCardId(
        string $cardId,
        bool $render = false
    ): array
    {
        $data = database()
            ->select(
                statement('select')
                    ->select(['id', '`date`', 'is_errata', '`text`'])
                    ->from(self::$table)
                    ->where('cards_id = :cardid')
                    ->orderBy(['`date` DESC'])
            )
            ->bind([':cardid' => $cardId])
            ->get();

        // Do not render ruling text as HTML
        if (!$render) return $data;

        // Render ruling text as HTML
        for ($i = 0, $len = count($data); $i < $len; $i++) {
            $data[$i]['text'] = render($data[$i]['text']);
        }

        return $data;
    }

    /**
     * Reads data for a single resource on db by its ID
     *
     * @param string $id ID of the resource
     * @param array $fields Fields to select, defaults to all
     * @param boolean $render Render the text?
     * @return array
     */
    public static function getById(
        string $id,
        array $fields = [],
        bool $render = false
    ): array
    {
        $data = database()
            ->select(statement('select')
                ->select($fields ?? '*')
                ->from(self::$table)
                ->where('id = :id')
                ->limit(1)
            )
            ->bind([':id' => $id])
            ->first();

        if (!$render) return $data;

        if ($render && isset($data['text'])) {
            $data['text'] = render($data['text']);
        }

        return $data;
    }
}
