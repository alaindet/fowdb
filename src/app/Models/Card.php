<?php

namespace App\Models;

use App\Exceptions\CardModelException;
use App\Base\Model;
use App\Services\FileSystem\FileSystem;

class Card extends Model
{
    public $table = 'cards';

    public $numeric = [
        'id',
        'sorted_id',
        'layout',
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

    // Removable name => types[]
    private $removables = [
        'no-cost' => [
            'Ruler',
            'J-Ruler',
            'Basic Magic Stone',
            'Special Magic Stone',
            'True Magic Stone'
        ],
        'no-attribute' => [
            'Basic Magic Stone',
            'Special Magic Stone',
            'True Magic Stone'
        ],
        'can-divinity' => [
            'Rune',
            'Master Rune',
        ],
        'can-battle' => [
            'J-Ruler',
            'Resonator',
            'Resonator (Stranger)',
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

    public function getRemovableFields(): array
    {
        $result = [];
        $name2bitValue = lookup('types.display');

        foreach ($this->removables as $label => $displayTypes) {
            $result[$label] = [];
            foreach ($displayTypes as $displayType) {
                $result[$label][] = $name2bitValue[$displayType];
            }
        }
        
        return $result;
    }

    public function getByCode(
        string $code,
        array $fields = [],
        array $fieldsToRender = [],
        bool $dev = false
    ): array
    {
    	$statement = statement('select')
          ->select($fields)
          ->from($this->table)
          ->where('code = :code')
          ->limit(3);
    
        $data = database()
            ->select($statement)
            ->bind([':code' => $code])
            // ->bind([':code' => "{$code}%"])
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

    /**
     * Returns data of the "next" resource based on the 'sorted_id' attribute
     * of the "previous" resource
     *
     * @param integer|string $previousSortedId The base sorted ID
     * @return array Next card's data
     */
    public function getNext(
        $previousSortedId,
        array $fields = [],
        array $fieldsToRender = []
    ): array
    {
        $sortedId = intval($previousSortedId) + 1;

        return $this->byField('sorted_id', $sortedId, $fields, $fieldsToRender);
    }

    /**
     * Regenerates cards.sorted_id field for all cards
     *
     * @return void
     */
    public static function buildAllSortId(): void
    {
        $statementPath = path_src("database/set-cards-sorted-id.sql");
        $statement = FileSystem::readFile($statementPath);
        database()->rawStatement($statement);
    }
}
