<?php

namespace App\Services\Database\Statement;

use App\Services\Database\Statement\Stringable;

abstract class SqlStatement
{
    /**
     * This trait exposes a single toString() method
     */
    use Stringable;

    // /**
    //  * All possible clauses child classes can have
    //  *
    //  * @var array
    //  */
    // public $clauses = [
    //     'SELECT' => [],
    //     'FROM' => '',
    //     'UPDATE' => '',
    //     'SET' => [],
    //     'INSERT INTO' => '',
    //     'VALUES' => [],
    //     'DELETE FROM' => '',
    //     'WHERE' => [],
    //     'GROUP BY' => [],
    //     'HAVING' => [],
    //     'ORDER BY' => [],
    //     'LIMIT' => -1,
    //     'OFFSET' => -1,
    // ];

    /**
     * Returns the final valid SQL statement as a string
     * Calls the only public method of the Stringable trait
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}
