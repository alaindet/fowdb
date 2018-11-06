<?php

namespace App\Models;

use App\Exceptions\CardModelException;

class Card
{
    public static $table = 'cards';

    public static function getByCode(
        string $code,
        array $fields = [],
        array $fieldsToRender = []
    ): array
    {
        $data = database()
            ->select(statement('select')
                ->select($fields)
                ->from(self::$table)
                ->where('code = :code')
                ->limit(3)
            )
            ->bind([':code' => $code])
            ->get();

        // Return raw data (default)
        if (empty($toRender)) return $data;

        // Render fields
        foreach ($data as &$item) {
            foreach ($fieldsToRender as $field) {
                $item[$field] = render($item[$field]);
            }
        }

        return $data;
    }

    public static function getById(
        string $id,
        array $fields = [],
        array $fieldsToRender = []
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

        // Return raw data (default)
        if (empty($fieldsToRender)) return $data;

        // Render fields
        foreach ($data as &$item) {
            foreach ($fieldsToRender as $field) {
                $item[$field] = render($item[$field]);
            }
        }

        return $data;
    }

    public static function getBaseIdById(string $id): int
    {
        $card = self::getById($id, ['narp', 'name']);

        if ((int) $card['narp'] === 0) return (int) $id;

        $baseCard = database()
            ->select(statement('select')
                ->select('id')
                ->from(self::$table)
                ->where(['name = :name'])
                ->limit(1)
            )
            ->bind([':name' => $card['name']])
            ->first();

        return (int) $baseCard['id'];
    }
    
    public static function getBaseIdByName(string $name): int
    {
        $baseCard = database()
            ->select(statement('select')
                ->select('id')
                ->from(self::$table)
                ->where([
                    'name = :name',
                    'narp = 0'
                ])
                ->limit(1)
            )
            ->bind([':name' => $name])
            ->first();

        // ERROR: Invalid card name
        if (empty($baseCard)) {
            throw new CardModelException('Invalid card name');
        }

        return (int) $baseCard['id'];
    }
}
