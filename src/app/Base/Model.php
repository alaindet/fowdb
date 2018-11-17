<?php

namespace App\Base;

use App\Base\Base;
use App\Exceptions\ModelException;

abstract class Model extends Base
{
    public function all(array $select = null): array
    {
        // ERROR: Missing table name
        if (!isset($this->table)) {
            throw new ModelException('Missing table name for model');
        }

        $fields = isset($select) ? implode(',', $select) : '*';
        
        return database()
            ->select(
                statement('select')
                    ->select($fields)
                    ->from($this->table)
            )
            ->get();
    }

    public function byId($id, array $select = null): array
    {
        // ERROR: Missing table name
        if (!isset($this->table)) {
            throw new ModelException('Missing table name for model');
        }

        $fields = isset($select) ? implode(',', $select) : '*';

        return database()
            ->select(
                statement('select')
                    ->select($fields)
                    ->from($this->table)
                    ->where('id = :id')
                    ->limit(1)
            )
            ->bind([':id' => intval($id)])
            ->first();
    }
}
