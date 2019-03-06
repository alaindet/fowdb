<?php

namespace App\Entities\Play\Format;

use App\Entities\Play\Format\Format;
use App\Entities\Play\Format\Formats;
use App\Services\Database\Database;
use App\Services\Database\Statement\SelectSqlStatement;

class FormatsRepository
{
    private $db;
    private $formatsSorting;
    private $formatsTable = 'game_formats';
    private $formatsEntity = Format::class;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->formatsSorting = [];
    }

    public function sorting(array $sorting): FormatsRepository
    {
        $this->formatsSorting = $sorting;
        return $this;
    }

    public function all(array $fields = []): Formats
    {
        $statement = (new SelectSqlStatement)
            ->select($fields)
            ->from($this->formatsTable)
            ->orderBy($this->formatsSorting);

        $items = $this->db
            ->select($statement)
            ->get($this->formatsEntity);

        $collection = (new Formats)
            ->set($items);

        return $collection;
    }

    public function nextAvailableId(): int
    {
        $statement = "SELECT MAX(id) AS max FROM {$this->formatsTable}";
        $item = database()->rawSelect($statement);
        $max = intval($item[0]['max']);
        return $max + 1;
    }
}
