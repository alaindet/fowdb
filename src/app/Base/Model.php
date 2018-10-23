<?php

namespace App\Base;

use App\Base\Base;
use App\Exceptions\ModelException;

abstract class Model extends Base
{
    public function all(array $select = null)
    {
        // ERROR: Missing table name
        if (!isset($this->table)) {
            throw new ModelException('Missing table name for model');
        }

        $fields = isset($select) ? implode(',', $select) : '*';
        
        return database()->get("SELECT {$fields} FROM {$this->table}");
    }
}
