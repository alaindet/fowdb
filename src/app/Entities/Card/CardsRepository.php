<?php

namespace App\Entities\Card;

use App\Entities\Card\Card;
use App\Entities\Card\Cards;
use App\Services\Database\Database;
use App\Services\Database\Statement\SelectSqlStatement;

class CardsRepository
{
    private $db = null;
    private $cardsTable = 'cards';
    private $cardsEntity = Card::class;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findById(int $id, array $fields = []): Card
    {
        $statement = (new SelectSqlStatement)
            ->select($fields)
            ->from($this->cardsTable)
            ->where('id = :id')
            ->limit(1);

        $item = $this->db
            ->select($statement)
            ->bind([':id' => $id])
            ->get($this->cardsEntity);

        return $item;
    }

    public function findByCode(string $code, array $fields = []): Card
    {
        $statement = (new SelectSqlStatement)
            ->select($fields)
            ->from($this->cardsTable)
            ->where('code = :code')
            ->limit(1);

        $item = $this->db
            ->select($statement)
            ->bind([':code' => $code])
            ->first($this->cardsEntity);

        return $item;
    }

    public function findAllByCode(string $code, array $fields = []): Cards
    {
        $statement = (new SelectSqlStatement)
            ->select($fields)
            ->from($this->cardsTable)
            ->where('code = :code')
            ->limit(3);

        $items = $this->db
            ->select($statement)
            ->bind([':code' => $code])
            ->get($this->cardsEntity);

        $collection = new Cards;
        $collection->set($items);

        return $collection;
    }
}
