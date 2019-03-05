<?php

namespace App\Entities\Play\Format;

use App\Entities\Play\Format\Format;
use App\Entities\Play\Format\Formats;
use App\Services\Database\Database;
use App\Services\Database\Statement\SelectSqlStatement;

class FormatsRepository
{
    private $db = null;
    private $formatsTable = 'game_formats';
    private $formatsEntity = Format::class;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function all(array $fields = []): Formats
    {
        $statement = (new SelectSqlStatement)
            ->select($fields)
            ->from($this->formatsTable);

        $items = $this->db
            ->select($statement)
            ->get($this->formatsEntity);

        $collection = (new Formats)
            ->set($items);

        return $collection;
    }
}
