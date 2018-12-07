<?php

namespace App\Models;

use App\Exceptions\CardModelException;
use App\Base\Model;

class Card extends Model
{
    public $table = 'cards';

    public $virtualAttributes = [
        '*short_code' => 'getShortCodeAttribute',
    ];

    public $numeric = [
        'id',
        'sorted_id',
        'back_side',
        'narp',
        'clusters_id',
        'sets_id',
        'num',
        'divinity',
        'free_cost',
        'total_cost',
        'atk',
        'def'
    ];

    public $removables = [
        'no-cost' => [
            'Ruler',
            'J-Ruler',
            'Magic Stone',
            'Special Magic Stone',
            'Special Magic Stone/True Magic Stone'
        ],
        'no-attribute' => [
            'Magic Stone',
            'Special Magic Stone',
            'Special Magic Stone/True Magic Stone'
        ],
        'can-battle' => [
            'J-Ruler',
            'Resonator'
        ]
    ];

    /**
     * Override Model::byId by casting numeric values as integers
     *
     * @param integer|string id
     * @param array $fields
     * @param array $fieldsToRender
     * @return array
     */
    public function byId(
        $id,
        array $fields = null,
        array $fieldsToRender = []
    ): array
    {
        $card = parent::byId($id, $fields, $fieldsToRender);
        foreach ($this->numeric as $field) {
            if (isset($card[$field])) {
                $card[$field] = intval($card[$field]);
            }
        }
        return $card;
    }

    public function getRemovableFields(string $feature = null): array
    {
        if (isset($feature)) return $this->removables[$feature];

        return $this->removables;
    }

    public function getByCode(
        string $code,
        array $fields = [],
        array $fieldsToRender = []
    ): array
    {
        $data = database()
            ->select(statement('select')
                ->select($fields)
                ->from($this->table)
                // ->where('code = :code')
                ->where('code LIKE :code')
                ->limit(3)
            )
            // ->bind([':code' => $code])
            ->bind([':code' => "{$code}%"])
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

    public function getBaseIdById(string $id): int
    {
        $card = $this->byId($id, ['narp', 'name']);

        if ((int) $card['narp'] === 0) return (int) $id;

        $baseCard = database()
            ->select(statement('select')
                ->select('id')
                ->from($this->table)
                ->where(['name = :name'])
                ->limit(1)
            )
            ->bind([':name' => $card['name']])
            ->first();

        return (int) $baseCard['id'];
    }
    
    public function getBaseIdByName(string $name): int
    {
        $baseCard = database()
            ->select(statement('select')
                ->select('id')
                ->from($this->table)
                ->where('name = :name')
                ->where('narp = 0')
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

    protected function getShortCodeAttribute(array &$resource): string
    {
        $bits = explode(' ', $resource['code'], 2);
        return $bits[0];
    }
}
